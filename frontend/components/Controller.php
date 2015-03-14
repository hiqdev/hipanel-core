<?php

namespace frontend\components;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
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

    /**
     * @param array $params - params array the will be used to create the model
     * @returns Model
     * @throws InvalidConfigException
     */
    static protected function newModel ($params = []) {
        throw new InvalidConfigException('Define newModel function');
    }

    /**
     * @param int $id
     * @throws NotFoundHttpException
     */
    protected function findModel ($id) {
        /** @noinspection PhpVoidFunctionResultUsedInspection */
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
