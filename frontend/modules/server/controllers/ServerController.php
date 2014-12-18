<?php

namespace app\modules\server\controllers;

use app\modules\server\models\ServerSearch;
use app\modules\server\models\Server;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;

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
        return $this->render('view', ['model' => $model]);
    }

    public function actionEnableVnc ($id) {
        $model      = $this->findModel($id);
        if (!$model->isOperable()) {
            throw new NotSupportedException('Server has a running task');
        }
        $model->vnc = $this->getVNCInfo($model, true);

        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('_vnc', ['model' => $model]);
        } else {
            return $this->render('_vnc', ['model' => $model]);
        }
    }

    public function actionReboot ($id) {
        $model      = $this->findModel($id);
        if (!$model->isOperable()) {
            throw new NotSupportedException('Server has a running task');
        }
        Server::perform('Reboot', ['id' => $model->id]);
        return $this->redirect(['view', 'id' => $model->id]);
    }

    private function getVNCInfo ($model, $enable = false) {
        $vnc['endTime'] = strtotime('+8 hours', strtotime($model->statuses['serverEnableVNC']));
        if ($vnc['endTime'] > time() || $enable) {
            $vnc['enabled'] = true;
            $vnc             = ArrayHelper::merge($vnc, $vnc_data = Server::perform('EnableVNC', ['id' => $model->id]));
        }
        return $vnc;
    }

    /**
     * @param int $id
     *
     * @return \app\modules\server\models\Server|null
     * @throws NotFoundHttpException
     */
    protected function findModel ($id) {
        if (($model = Server::findOne(['id' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
