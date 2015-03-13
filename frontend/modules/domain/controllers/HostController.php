<?php

namespace frontend\modules\domain\controllers;

use frontend\modules\domain\models\HostSearch;
use frontend\modules\domain\models\Host;
use frontend\components\hiresource\HiResException;
use frontend\components\CrudController;
use frontend\models\Ref;
use yii\base\NotSupportedException;
use yii\filters\VerbFilter;
use frontend\components\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class HostController extends CrudController
{
    /**
     * All of security-aware methods are allowed only with POST requests
     *
     * @return array
     */
    public function behaviors () {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'update'               => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex () {
        $searchModel  = new HostSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView ($id) {
        $model = $this->findModel($id);
        return $this->render('view', compact('model'));
    }

    /**
     * @param int $id
     *
     * @return \frontend\modules\domain\models\Host|null
     * @throws NotFoundHttpException
     */
    protected function findModel ($id) {
        $model = Host::findOne(['id' => $id]);
        if ($model===null) throw new NotFoundHttpException('The requested page does not exist.');
        return $model;
    }

}
