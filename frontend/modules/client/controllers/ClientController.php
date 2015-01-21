<?php

namespace app\modules\client\controllers;

use app\modules\client\models\ClientSearch;
use app\modules\client\models\Client;
use frontend\components\hiresource\HiResException;
use yii\helpers\ArrayHelper;
use Yii;
use yii\web\Response;

class ClientController extends DefaultController
{
    public function actionIndex ($tpl = null) {
        // Fetch clients data from API
        $searchModel = new ClientSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'dataProvider'  => $dataProvider,
            'searchModel'   => $searchModel,
            'tpl'           => $tpl,
        ]);
    }

    public function actionView ($id) {
        $params = [
            'with_contact'          => 1,
            'with_domains_count'    => 1,
            'with_servers_count'    => 1,
            'with_contacts_count'   => 1,
        ];
        return $this->render('view', ['model' => $this->findModel($id, 'Client', $params),]);
    }

    private function actionUserList ($search, $id) {
        $out = ['more' => true];
        if (!is_null($search)) {
            $data = \app\modules\client\models\Client::find()->where($search)->getList();
            $res = [];
            foreach ($data as $item) {
                $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
            }
            $out['results'] = $res;
        } elseif ($id != 0) {
            $out['results'] = [
                'id' => $id,
                'text' => \app\modules\client\models\Client::find()->where([
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


    public function actionSetCredit () {
        
    }

    public function actionSetLanguage () {
    }

    public function actionSetTariffs () {
    }

    public function actionUpdate () {
    }

    public function actionEnableBlock () {
    }

    public function actionDisableBlock () {
    }

    public function actionDelete ($id) {
        $this->findModel($id, 'Client')->delete();
        return $this->redirect(['index']);
    }
}
