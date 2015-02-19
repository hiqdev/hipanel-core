<?php

namespace frontend\components;

use yii\filters\AccessControl;
use yii\web\Response;

/**
 * Site controller
 */
class Controller extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function renderJson ($data) {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $data;
    }

    public function renderJsonp ($data) {
        \Yii::$app->response->format = Response::FORMAT_JSONP;
        return $data;
    }

}
