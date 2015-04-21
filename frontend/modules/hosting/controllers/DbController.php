<?php

namespace frontend\modules\hosting\controllers;

use frontend\components\actions\PerformAction;
use frontend\components\CrudController;
use frontend\modules\hosting\models\Db;
use Yii;
use yii\filters\VerbFilter;

class DbController extends CrudController
{
    public function behaviors () {
        return [
            'verbs' => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'create' => ['get', 'post']
                ]
            ]
        ];
    }

    public function actionCreate () {
        $model = new Db(['scenario' => 'create']);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionSetDescription () {
        return $this->perform();
    }

    public function actionTruncate () {
        return $this->perform([
            'success' => [
                'message' => Yii::t('app', 'DB truncate task has been created successfully'),
            ],
            'error'   => [
                'message' => Yii::t('app', 'Error while truncating DB'),
            ],
            'result'  => [
                'ajax'  => ['return', 'ajax'],
                'pjax'  => ['action', ['view', 'id' => '{id}']],
            ]
        ]);
    }
}
