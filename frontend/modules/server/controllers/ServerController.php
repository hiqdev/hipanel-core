<?php

namespace app\modules\server\controllers;

use app\modules\server\models\ServerSearch;
use app\modules\server\models\Server;
use app\modules\server\models\OsimageSearch;
use app\modules\server\models\Osimage;
use frontend\components\hiresource\HiResException;
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
        return $this->render('view', ['model' => $model, 'osimages' => $this->getOsimages()]);
    }

    public function actionEnableVnc ($id) {
        $model = $this->findModel($id);
        $model->checkOperable();
        $model->vnc = $this->getVNCInfo($model, true);

        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('_vnc', ['model' => $model]);
        } else {
            return $this->render('view', ['model' => $model]);
        }
    }

    /**
     * @param \app\modules\server\models\Server $model
     * @param bool $enable
     *
     * @return array
     * @throws HiResException
     */
    private function getVNCInfo ($model, $enable = false) {
        $vnc['endTime'] = strtotime('+8 hours', strtotime($model->statuses['serverEnableVNC']));
        if (($vnc['endTime'] > time() || $enable) && $model->isOperable()) {
            $vnc['enabled'] = true;
            $vnc            = ArrayHelper::merge($vnc, Server::perform('EnableVNC', ['id' => $model->id]));
        }
        return $vnc;
    }

    public function actionReboot ($id) {
        return $this->operate([
            'id'             => $id,
            'action'         => 'Reset',
            'errorMessage'   => 'Error while rebooting',
            'successMessage' => 'Reboot task has been successfully added to queue',
        ]);
    }

    public function actionReset ($id) {
        return $this->operate([
            'id'             => $id,
            'action'         => 'Reset',
            'errorMessage'   => 'Error while resetting',
            'successMessage' => 'Reset task has been successfully added to queue',
        ]);
    }

    public function actionShutdown ($id) {
        return $this->operate([
            'id'             => $id,
            'action'         => 'Shutdown',
            'errorMessage'   => 'Error while shutting down',
            'successMessage' => 'Shutdown task has been successfully added to queue',
        ]);
    }

    public function actionPowerOff ($id) {
        return $this->operate([
            'id'             => $id,
            'action'         => 'PowerOff',
            'errorMessage'   => 'Error while turning power off',
            'successMessage' => 'Power off task has been successfully added to queue',
        ]);
    }

    public function actionPowerOn ($id) {
        return $this->operate([
            'id'             => $id,
            'action'         => 'PowerOn',
            'errorMessage'   => 'Error while turning power on',
            'successMessage' => 'Power on task has been successfully added to queue',
        ]);
    }

    public function actionBootLive ($id) {
        return $this->operate([
            'id'             => $id,
            'action'         => 'BootLive',
            'errorMessage'   => 'Error while booting live CD',
            'successMessage' => 'Live CD booting task has been successfully added to queue',
        ]);
    }

    /**
     * @param $options
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\NotSupportedException
     */
    private function operate ($options) {
        $model = $this->findModel($options['id']);
        $model->checkOperable();
        try {
            Server::perform($options['action'], $options['params'] ? $options['params']($model) : ['id' => $model->id]);
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', $options['successMessage']));
        } catch (HiResException $e) {
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', $options['errorMessage']));
        }
        return $this->redirect(['view', 'id' => $model->id]);
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

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    protected function getOsimages () {
        if (($models = Osimage::find()->all()) !== null) {
            return $models;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
