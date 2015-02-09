<?php

namespace app\modules\server\controllers;

use app\modules\server\models\ServerSearch;
use app\modules\server\models\Server;
use app\modules\server\models\Osimage;
use frontend\components\hiresource\HiResException;
use frontend\components\Re;
use frontend\controllers\HipanelController;
use frontend\models\Ref;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class ServerController extends HipanelController
{
    /**
     * All of security-aware methods are allowed only with POST requests
     * @return array
     */
    public function behaviors () {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'enableVnc'         => ['post'],
                    'reboot'            => ['post'],
                    'reset'             => ['post'],
                    'shutdown'          => ['post'],
                    'powerOff'          => ['post'],
                    'bootLive'          => ['post'],
                    'regenRootPassword' => ['post'],
                    'reinstall'         => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex () {
        $searchModel  = new ServerSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'osimages'     => $this->getOsimages()
        ]);
    }

    public function actionView ($id) {
        $model      = $this->findModel($id);
        $model->vnc = $this->getVNCInfo($model);

        $osimages         = $this->getOsimages();
        $osimageslivecd   = $this->getOsimagesLiveCd();
        $grouped_osimages = $this->getGroupedOsimages($osimages);
        $panels           = $this->getPanelTypes();

        return $this->render('view', compact('model', 'osimages', 'osimageslivecd', 'grouped_osimages', 'panels'));
    }

    /**
     * Enables VNC on the server
     *
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\base\NotSupportedException
     */
    public function actionEnableVnc ($id) {
        $model = $this->findModel($id);
        $model->checkOperable();
        $model->vnc = $this->getVNCInfo($model, true);

        return $this->actionView($id);
    }

    /**
     * Reinstalls OS on the server
     *
     * @param $id
     * @return \yii\web\Response
     */
    public function actionReinstall ($id) {
        return $this->operate([
            'id'             => $id,
            'params'         => function ($model) {
                return [
                    'id'      => $model->id,
                    'osimage' => \Yii::$app->request->post('osimage'),
                    'panel'   => \Yii::$app->request->post('panel')
                ];
            },
            'action'         => 'Resetup',
            'errorMessage'   => 'Error while server re-intalling',
            'successMessage' => 'Server reinstalling task has been successfully added to queue',
        ]);
    }

    /**
     * Gets info of VNC on the server
     *
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
            'action'         => 'Reboot',
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

    public function actionBootLive ($id, $osimage) {
        return $this->operate([
            'id'             => $id,
            'params'         => function ($model) use ($osimage) {
                return ['id' => $model->id, 'osimage' => $osimage];
            },
            'action'         => 'BootLive',
            'errorMessage'   => 'Error while booting live CD',
            'successMessage' => 'Live CD booting task has been successfully added to queue',
        ]);
    }

    public function actionRegenRootPassword ($id) {
        return $this->operate([
            'id'             => $id,
            'action'         => 'RegenRootPassword',
            'errorMessage'   => 'Error while password regeneration',
            'successMessage' => 'Password regenerating task has been successfully added to queue',
        ]);
    }

    /**
     * @param array $options
     * options['params'] - callable ($model)
     *
     * @return \yii\web\Response
     * @throws NotFoundHttpException
     * @throws \yii\base\NotSupportedException
     */
    private function operate ($options) {
        $model = $this->findModel($options['id']);
        $model->checkOperable();
        try {
            $params = $options['params'] ? $options['params']($model) : ['id' => $model->id];
            Server::perform($options['action'], $params);
            \Yii::$app->getSession()->setFlash('success', \Yii::t('app', $options['successMessage']));
        } catch (HiResException $e) {
            \Yii::$app->getSession()->setFlash('error', \Yii::t('app', $e->errorInfo));
        }

        return $this->actionView($options['id']);
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

    protected function getOsimages () {
        if (($models = Osimage::find()->all()) !== null) {
            return $models;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getOsimagesLiveCd () {
        if (($models = Osimage::findAll(['livecd' => true])) !== null) {
            return $models;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getPanelTypes () {
        return ArrayHelper::map(Ref::find()->where(['gtype' => 'type,panel'])
                                   ->getList(), 'gl_key', function ($o) { return Re::l($o->gl_value); });
    }

    /**
     * Generates array of osimages data, grouped by different fields to display on the website
     * @param $images Array of osimages models to be proceed
     *
     * @return array
     */
    protected function getGroupedOsimages ($images) {
        $isp = 1; /// TODO: temporary enabled for all tariff. Redo with check of tariff resources

        $softpacks = [];
        $oses      = [];
        $vendors   = [];
        foreach ($images as $image) {
            /** @var Osimage $image */
            $os            = $image->os;
            $name          = $image->getFullOsName();
            $panel         = $image->getPanelName();
            $system        = $image->getFullOsName('');
            $softpack_name = $image->getSoftPackName();
            $softpack      = $image->getSoftPack();

            if (!array_key_exists($system, $oses)) {
                $vendors[$os]['name']          = $os;
                $vendors[$os]['oses'][$system] = $name;
                $oses[$system]                 = ['vendor' => $os, 'name' => $name];
            }

            if ($panel != 'isp' || ($panel == 'isp' && $isp)) {
                $data = [
                    'name'        => $softpack_name,
                    'description' => preg_replace('/^ISPmanager - /', '', $softpack['description']),
                    'osimage'     => $image->osimage
                ];

                if ($softpack['soft']) {
                    $html_desc = [];
                    foreach ($softpack['soft'] as $soft => $soft_info) {
                        $soft_info['description'] = preg_replace('/,([^\s])/', ', $1', $soft_info['description']);

                        $html_desc[]         = "<b>{$soft_info['name']} {$soft_info['version']}</b>: <i>{$soft_info['description']}</i>";
                        $data['soft'][$soft] = [
                            'name'        => $soft_info['name'],
                            'version'     => $soft_info['version'],
                            'description' => $soft_info['description']
                        ];
                    }
                    $data['html_desc'] = implode("<br>", $html_desc);
                }
                $oses[$system]['panel'][$panel]['softpack'][$softpack_name] = $data;
                $softpacks[$panel][$softpack_name]                          = $data;
            } else {
                $oses[$system]['panel'][$panel] = false;
            }
        }


        foreach ($oses as $system => $os) {
            $delete = true;
            foreach ($os['panel'] as $panel => $info) {
                if ($info !== false) $delete = false;
            }
            if ($delete) unset($vendors[$os['vendor']]['oses'][$system]);
        }

        return compact('vendors', 'oses', 'softpacks');
    }
}
