<?php

namespace frontend\modules\client\controllers;

use frontend\components\CrudController;
use yii\base\Model;
use frontend\modules\client\models\ClientSearch;
use frontend\modules\client\models\Client;
use frontend\components\hiresource\HiResException;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\Response;
use yii\web\NotFoundHttpException;

class ClientController extends CrudController {

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

    private function actionUserList ($input) {
        $out = ['more' => true];
        $class = "{$this->path}\\{$this->class}";
        if (!is_null($input['search'])) {
            $data = $class::find()->where($input['search'])->getList();
            $res = [];
            foreach ($data as $key => $item) {
                $res[] = ['id' => $key, 'text' => $item];
            }
            $out['results'] = $res;
        } elseif ($input['id'] != 0) {
            $out['results'] = [
                'id' => $input['id'],
                'text' => $class::find()->where([
                    'id' => $input['id'],
                ])->one()->login
            ];
        } else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        return $this->renderJson($out);
    }

    public function actionClientAllList ($search = null, $id = null, $format = 'json') {
        $search = $search === null ? null : ['client_like' => $search];
        return $this->actionUserList(compact('search','id','format'));
    }

    public function actionClientList ($search = null, $id = null, $format = 'json') {
        $search = $search === null ? null : ['client_like' => $search, 'type' => 'client'];
        return $this->actionUserList(compact('search','id','format'));
    }

    public function actionManagerList ($search = null, $id = null, $format = 'json') {
        $search = $search === null ? null : ['client_like' => $search, 'type' => 'manager' ];
        return $this->actionUserList(compact('search','id','format'));
    }

    public function actionAdminList ($search = null, $id = null, $format = 'json') {
        $search = $search === null ? null : ['client_like' => $search, 'type' => 'admin' ];
        return $this->actionUserList(compact('search','id','format'));
    }

    public function actionSellerList ($search = null, $id = null, $format = 'json') {
        $search = $search === null ? null : ['client_like' => $search, 'type' => 'reseller' ];
        return $this->actionUserList(compact('search','id','format'));
    }

    public function actionCanManageList ($search = null, $id = null, $format = 'json') {
        $search = $search === null ? null : ['client_like' => $search, 'manager_only' => 'true' ];
        return $this->actionUserList(compact('search','id','format'));
    }

    private function actionPrepareRender ($params = [], $addFuncs) {
        $class = "{$this->path}\\{$this->class}Search";
        $searchModel = new $class();
        $dataProvider = $searchModel->search( $params );
        foreach ($addFuncs as $func => $param) {
            $additional[$func] = $this->{$func}($param);
        }
        return [
                'dataProvider'  => $dataProvider,
                'searchModel'   => $searchModel,
                'additional'    => $additional,
        ];
    }

    private function actionPrepareDataToUpdate ($action, $params, $scenario) {
        $data = [];
        foreach ($params['ids'] as $id => $values) {
            if (is_array($values)) {
                foreach ($values as $key => $value) $data[$id][$key] = $value;
            }
            $models[$id] = Client::findOne(compact('id'));
            $models[$id]->scenario = $scenario;
        }
        try {
            foreach ($models as $id => $model) {
                if (!$model->load($data[$id]) || !$model->validate()) {
                    unset($data[$id]);
                }
            }
            if (!empty($data)) {
                Client::perform($action, $data, true);
            } else {
                return false;
            }
        } catch (HiResException $e) {
            return false;
        }
        return true;
    }

    private function recursiveSearch ($array, $field) {
        if (is_array($array)) {
            if (\yii\helpers\BaseArrayHelper::keyExists($field, $array)) return true;
            else {
                foreach ($array as $key => $value) {
                    if (is_array($value)) $res = $res ? : $this->recursiveSearch($value, $field);
                }
                return $res;
            }
        }
        return false;
    }

    private function checkException ($id, $ids, $post) {
        if (!$id && !$ids &&!$post['id'] && !$post['ids']) throw new NotFoundHttpException('The requested page does not exist.');
        return true;
    }

    private function actionRenderPage ($page, $queryParams, $action = [], $addFunc = []) {
        return Yii::$app->request->isAjax
            ? $this->renderPartial($page, ArrayHelper::merge($this->actionPrepareRender($queryParams, $addFunc), $action))
            : $this->render($page, ArrayHelper::merge($this->actionPrepareRender($queryParams, $addFunc), $action));
    }

    private function actionPerform ($row) {
        $this->checkException ($row['id'], $row['ids'], Yii::$app->request->post());
        $id = $row['id'] ? : Yii::$app->request->post('id');
        $ids = $row['ids'] ? : Yii::$app->request->post('ids');
        if (Yii::$app->request->isAjax && !$id) {
            if ($this->actionPrepareDataToUpdate($row['action'] , Yii::$app->request->post(), $row['scenario'])) {
                return ['state' => 'success', 'message' => \Yii::t('app', $row['action']) ];
            } else {
                return ['state' => 'error', 'message' => \Yii::t('app', 'Something wrong')];
            }
        }
        $check = true;
        foreach ($row['required'] as $required) {
            if (!$this->recursiveSearch(Yii::$app->request->post(), $required)) {
                $check = false;
                break;
            }
        }
        if (!$id && $check) {
            if ($this->actionPrepareDataToUpdate($row['action'], Yii::$app->request->post(), $row['scenario'])) {
                \Yii::$app->getSession()->setFlash('success', \Yii::t('app', '{0} was successful', $row['action']));
            } else {
                \Yii::$app->getSession()->setFlash('error',  \Yii::t('app', 'Something wrong'));
            }
            return $this->redirect(Yii::$app->request->referrer);
        }
        $ids = $ids ? : [ 'id' => $id ];
        $queryParams = [ 'ids' => implode(',', $ids) ];
        return $this->actionRenderPage($row['page'], $queryParams, ['action' => $row['subaction']], $row['add']);
    }

    public function actionSetCredit ($id = null, $ids = []) {
        return $this->actionPerform([
            'id'        => $id,
            'ids'       => $ids,
            'action'    => 'SetCredit',
            'required'  => [ 'credit', 'id' ],
            'page'      => 'set-credit',
            'scenario'  => 'setcredit',
        ]);
    }

    /// TODO: implement
    public function actionSetLanguage ($id = null, $ids = []) {
        return $this->actionPerform([
            'id'        => $id,
            'ids'       => $ids,
            'action'    => 'SetLanguage',
            'required'  => [ 'id', 'language' ],
            'page'      => 'language',
            'scenario'  => 'setlanguage',
        ]);
    }

    /// TODO: implement
    public function actionSetTariffs () {
        $this->checkException ($id, $ids, Yii::$app->request->post());
        return $this->renderPartial('set-tariffs', ['model' => $this->findModel($id)]);
    }

    /// TODO: implement
    public function actionChangeType () {
        $this->checkException ($id, $ids, Yii::$app->request->post());
        return $this->renderPartial('change-type', ['model' => $this->findModel($id)]);
    }

    /// TODO: implement
    public function actionChangeSeller () {
        $this->checkException ($id, $ids, Yii::$app->request->post());
        return $this->renderPartial('change-seller', ['model' => $this->findModel($id)]);
    }

    /// TODO: implement
    public function actionChangeLogin () {
        $this->checkException ($id, $ids, Yii::$app->request->post());
        return $this->renderPartial('change-login', ['model' => $this->findModel($id)]);
    }

    /// TODO: implement
    public function actionCheckLogin ($login) {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $out;
    }

    public function actionSetSeller ($id = null, $ids = []) {
        return $this->actionPerform([
            'id'        => $id,
            'ids'       => $ids,
            'action'    => "setSeller",
            'required'  => [ 'id', 'seller_id' ],
            'page'      => 'set-seller',
            'scenario'  => 'setseller',
            'add'       => ['actionUserList' => ['search' => ['type' => 'reseller', 'limit' => 'ALL'], 'format' => '']],
        ]);
    }

    private function actionDoBlock ($id = null, $ids = [], $action = 'enable') {
        return $this->actionPerform([
            'id'        => $id,
            'ids'       => $ids,
            'action'    => ucfirst($action) . "Block",
            'subaction' => $action,
            'required'  => [ 'type', 'comment', 'id' ],
            'page'      => 'block',
            'scenario'  => 'setblock',
        ]);
   }

    public function actionEnableBlock ($id = null, $ids = []) {
        return $this->actionDoBlock($id, $ids, 'enable');
    }

    public function actionDisableBlock ($id = null, $ids = []) {
        return $this->actionDoBlock($id, $ids, 'disable');
    }

    private function actionDoPincode ($id = null, $ids = [], $action = 'enable') {

    }

    public function actionEnablePincode ($id = null, $ids = []) {
        return $this->actionDoPincode($id, $ids, 'enable');
    }

    public function actionDisablePincode ($id = null, $ids = []) {
        return $this->actionDoPincode($id, $ids, 'disable');
    }

    public function actionSetTmpPwd () {
        y
    }

}
