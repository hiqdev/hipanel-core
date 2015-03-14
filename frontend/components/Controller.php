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

    static protected function newModel () {
        throw new InvalidConfigException('Define newModel function');
    }

    /**
     * @param int $id
     * @throws NotFoundHttpException
     */
    protected function findModel ($id) {
        $model = static::newModel()->findOne(['id' => $id]);
        if ($model===null) throw new NotFoundHttpException('The requested page does not exist.');
        return $model;
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
