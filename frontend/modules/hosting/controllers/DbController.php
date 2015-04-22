<?php

namespace frontend\modules\hosting\controllers;

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

    public function actionDelete () {
        return $this->perform([
            'success'  => [
                'message' => Yii::t('app', 'DB deleting task has been created successfully'),
                'result'  => [
                    'POST pjax' => ['action', ['index'], 'addFlash' => true, 'changeUrl' => function ($model) {
                        return ['index'];
                    }]
                ],
                'addFlash' => true
            ],
            'error'    => [
                'message' => Yii::t('app', 'Error while deleting DB'),
                'result'  => [
                    'POST pjax' => ['action', ['view', function ($model) { return ['id' => $model->id]; }], 'addFlash' => true]
                ],
            ],
        ]);
    }

    public function actionTruncate () {
        return $this->perform([
            'success'  => [
                'message' => Yii::t('app', 'DB truncate task has been created successfully'),
            ],
            'error'    => [
                'message' => Yii::t('app', 'Error while truncating DB'),
            ],
            'result'   => [
                'pjax' => [
                    'action',
                    ['view', function ($model) { return ['id' => $model->id]; }],
                ]
            ],
            'addFlash' => true
        ]);
    }
}
