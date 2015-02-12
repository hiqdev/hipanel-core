<?php

namespace frontend\modules\client\controllers;

use frontend\modules\client\models\ClientSearch;
use frontend\modules\client\models\Client;
use frontend\components\hiresource\HiResException;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\Response;
use yii\web\NotFoundHttpException;

class ClientController extends DefaultController {

    protected $class    = 'Client';
    protected $path     = 'frontend\modules\client\models';
    protected $tpl      = [
        '_tariff'   => '_tariff',
        '_card'     => '_card',
    ];

    public function actionView ($id) {
        $params = [
            'with_contact'          => 1,
            'with_domains_count'    => 1,
            'with_servers_count'    => 1,
            'with_contacts_count'   => 1,
        ];
        return $this->render('view', ['model' => $this->findModel($id, $params),]);
    }

    private function actionUserList ($search, $id) {
        $out = ['more' => true];
        $class = "{$this->path}\\{$this->class}";
        if (!is_null($search)) {
            $data = $class::find()->where($search)->getList();
            $res = [];
            foreach ($data as $item) {
                $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
            }
            $out['results'] = $res;
        } elseif ($id != 0) {
            $out['results'] = [
                'id' => $id,
                'text' => $class::find()->where([
                    'id' => $id,
                ])->one()->login
            ];
        } else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $out;
    }

    public function actionClientAllList ($search = null, $id = null) {
        $search = $search === null ? null : ['client_like' => $search];
        return $this->actionUserList($search, $id);
    }

    public function actionClientList ($search = null, $id = null) {
        $search = $search === null ? null : ['client_like' => $search, 'type' => 'client'];
        return $this->actionUserList($search, $id);
    }

    public function actionManagerList ($search = null, $id = null) {
        $search = $search === null ? null : ['client_like' => $search, 'type' => 'manager' ];
        return $this->actionUserList($search, $id);
    }

    public function actionAdminList ($search = null, $id = null) {
        $search = $search === null ? null : ['client_like' => $search, 'type' => 'admin' ];
        return $this->actionUserList($search, $id);
    }

    public function actionSellerList ($search = null, $id = null) {
        $search = $search === null ? null : ['client_like' => $search, 'type' => 'reseller' ];
        return $this->actionUserList($search, $id);
    }

    public function actionCanManageList ($search = null, $id = null) {
        $search = $search === null ? null : ['client_like' => $search, 'manager_only' => 'true' ];
        return $this->actionUserList($search, $id);
    }

    private function _actionPrepareRender ($action, $params = []) {
        $class = "{$this->path}\\{$this->class}Search";
        $searchModel = new $class();
        $dataProvider = $searchModel->search( $params );
        return [
                'dataProvider'  => $dataProvider,
                'searchModel'   => $searchModel,
        ];
    }

    private function _actionPrepareDataToUpdate ($action, $params) {
        $data = [];
        foreach ($params as $id => $values) {
            foreach ($values as $key => $value) $data[$id][$key] = $value;
        }
        try {
            Client::perform($action, $data, true);
        } catch (HiResException $e) {
            return false;
        }
        return true;
    }

    private function _recursiveSearch ($array, $field) {
        if (is_array($array)) {
            if (\yii\helpers\BaseArrayHelper::keyExists($field, $array)) return true;
            else {
                foreach ($array as $key => $value) {
                    if (is_array($value)) $res = $res ? : $this->_recursiveSearch($value, $field);
                }
                return $res;
            }
        }
        return false;
    }

    private function _checkException ($id, $ids, $post) {
        if (!$id && !$ids &&!$post['id'] && !$post['ids']) throw new NotFoundHttpException('The requested page does not exist.');
        return true;
    }

    public function actionSetCredit ($id = null, $ids = []) {
        $this->_checkException ($id, $ids, Yii::$app->request->post());
        $id = $id ? : Yii::$app->request->post('id');
        $ids = $ids ? : Yii::$app->request->post('ids');
        if (Yii::$app->request->isAjax && !$id) {
            if ($this->_actionPrepareDataToUpdate('SetCredit', Yii::$app->request->post())) {
                return ['state' => 'success', 'message' => \Yii::t('app', 'Credits were setted') ];
            } else {
                return ['state' => 'error', 'message' => \Yii::t('app', 'Something wrong')];
            }
        }
        if (!$id && $this->_recursiveSearch(Yii::$app->request->post(), 'credit') ) {
            if ($this->_actionPrepareDataToUpdate('SetCredit', Yii::$app->request->post())) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('app', 'Credits has been set successfully'));
            } else {
                \Yii::$app->getSession()->setFlash('error',  \Yii::t('app', 'Something wrong'));
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        $ids = $ids ? : [ 'id' => $id ];
        $queryParams = [ 'ids' => implode(',', $ids) ];
        return Yii::$app->request->isAjax
            ? $this->renderPartial('set-credit', $this->_actionPrepareRender('set-credit', $queryParams))
            : $this->render('set-credit', $this->_actionPrepareRender('set-credit', $queryParams));
    }

    /// TODO: implement
    public function actionSetLanguage () {
        return $this->renderPartial('set-language', ['model' => $this->findModel($id)]);
    }

    /// TODO: implement
    public function actionSetTariffs () {
        return $this->renderPartial('set-tariffs', ['model' => $this->findModel($id)]);
    }

    private function actionDoBlock ($id = null, $ids = [], $action = 'enable') {
        $this->_checkException ($id, $ids, Yii::$app->request->post());
        $id = $id ? : Yii::$app->request->post('id');
        $ids = $ids ? : Yii::$app->request->post('ids');
        if (Yii::$app->request->isAjax && !$id) {
            if ($this->_actionPrepareDataToUpdate(ucfirst($action) . "Block", Yii::$app->request->post())) {
                return ['state' => 'success', 'message' => \Yii::t('app', ucfirst($action) . 'd') ];
            } else {
                return ['state' => 'error', 'message' => \Yii::t('app', 'Something wrong')];
            }
        }
        if (!$id && $this->_recursiveSearch(Yii::$app->request->post(), 'type') && $this->_recursiveSearch(Yii::$app->request->post(), 'comment') ) {
            if ($this->_actionPrepareDataToUpdate('SetCredit', Yii::$app->request->post())) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('app', '{0} block was successful', $action));
            } else {
                \Yii::$app->getSession()->setFlash('error',  \Yii::t('app', 'Something wrong'));
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        $ids = $ids ? : [ 'id' => $id ];
        $queryParams = [ 'ids' => implode(',', $ids) ];
         return Yii::$app->request->isAjax ?
             $this->renderPartial('block', ArrayHelper::merge($this->_actionPrepareRender('disable-block', $queryParams), ['action' => $action]))
             : $this->render('block', ArrayHelper::merge($this->_actionPrepareRender('disable-block', $queryParams), ['action' => $action]));
   }

    public function actionEnableBlock ($id = null, $ids = []) {
        return $this->actionDoBlock($id, $ids, 'enable');
    }

    public function actionDisableBlock ($id = null, $ids = []) {
        return $this->actionDoBlock($id, $ids, 'disable');
    }
}
