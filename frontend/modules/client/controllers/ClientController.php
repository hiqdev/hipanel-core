<?php

namespace app\modules\client\controllers;

use app\modules\client\models\ClientSearch;
use app\modules\client\models\Client;
use frontend\components\hiresource\HiResException;
use yii\helpers\ArrayHelper;
use Yii;

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

    public function actionClientAllList ($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = \app\modules\client\models\Client::find()->where(['client_like' => $search])->getList();
            $res = [];
            foreach ($data as $item) {
                $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
            }
            $out['results'] = $res;
        }
        elseif ($id != 0) {
            $out['results'] = [
                'id' => $id,
                'text' => \app\modules\client\models\Client::find()->where([
                    'id' => $id,
                ])->one()->login
            ];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo \yii\helpers\Json::encode($out);
    }


    public function actionClientList ($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = \app\modules\client\models\Client::find()->where(['client_like' => $search, 'type' => 'client'])->getList();
            $res = [];
            foreach ($data as $item) {
                $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
            }
            $out['results'] = $res;
        }
        elseif ($id != 0) {
            $out['results'] = [
                'id' => $id,
                'text' => \app\modules\client\models\Client::find()->where([
                    'id' => $id,
                ])->one()->login
            ];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo \yii\helpers\Json::encode($out);
    }

    public function actionManagerList ($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = \app\modules\client\models\Client::find()->where(['client_like' => $search, 'type' => 'manager' ])->getList();
            $res = [];
            foreach ($data as $item) {
                $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
            }
            $out['results'] = $res;
        }
        elseif ($id != 0) {
            $out['results'] = [
                'id' => $id,
                'text' => \app\modules\client\models\Client::find()->where([
                    'id' => $id,
                ])->one()->login
            ];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo \yii\helpers\Json::encode($out);
    }

    public function actionAdminList ($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = \app\modules\client\models\Client::find()->where(['client_like' => $search, 'type' => 'admin' ])->getList();
            $res = [];
            foreach ($data as $item) {
                $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
            }
            $out['results'] = $res;
        }
        elseif ($id != 0) {
            $out['results'] = [
                'id' => $id,
                'text' => \app\modules\client\models\Client::find()->where([
                    'id' => $id,
                ])->one()->login
            ];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo \yii\helpers\Json::encode($out);
    }

    public function actionSellerList ($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = \app\modules\client\models\Client::find()->where(['client_like' => $search, 'type' => 'reseller' ])->getList();
            $res = [];
            foreach ($data as $item) {
                $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
            }
            $out['results'] = $res;
        }
        elseif ($id != 0) {
            $out['results'] = [
                'id' => $id,
                'text' => \app\modules\client\models\Client::find()->where([
                    'id' => $id,
                ])->one()->login
            ];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo \yii\helpers\Json::encode($out);
    }

    public function actionCanManageList ($search = null, $id = null) {
        $out = ['more' => false];
        if (!is_null($search)) {
            $data = \app\modules\client\models\Client::find()->where(['client_like' => $search, 'manager_only' => 'true' ])->getList();
            $res = [];
            foreach ($data as $item) {
                $res[] = ['id' => $item->gl_key, 'text' => $item->gl_value];
            }
            $out['results'] = $res;
        }
        elseif ($id != 0) {
            $out['results'] = [
                'id' => $id,
                'text' => \app\modules\client\models\Client::find()->where([
                    'id' => $id,
                ])->one()->login
            ];
        }
        else {
            $out['results'] = ['id' => 0, 'text' => 'No matching records found'];
        }
        echo \yii\helpers\Json::encode($out);
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
