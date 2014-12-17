<?php

namespace app\modules\server\controllers;

use app\modules\server\models\ServerSearch;
use app\modules\server\models\Server;

class ServerController extends \yii\web\Controller {
    public function actionIndex () {
        $searchModel  = new ServerSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    public function actionView ($id) {
        $model      = $this->findModel($id);
        $model->vnc = $this->getVNCInfo($model);
        return $this->render('view',
        [
            'model' => $model,
        ]);
    }

    private function getVNCInfo ($model) {
        $vnc['endTime'] = strtotime('+8 hours', strtotime($model->statuses['serverEnableVNC']));

        if ($vnc['endTime'] > time()) {
            $vnc['leftTime'] = $vnc['endTime'] - time();
        }

        return $vnc;
    }

    protected function findModel ($id) {
        $condition = ['id' => $id, 'show_deleted' => true, 'with_request' => true, 'with_discounts' => true];

        if (($model = Server::findOne($condition)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
