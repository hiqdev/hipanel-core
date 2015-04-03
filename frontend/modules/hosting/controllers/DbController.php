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
                'class' => VerbFilter::className(),
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
}
