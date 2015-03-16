<?php

namespace frontend\components;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\Inflector;
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
     * @returns Main Model class name
     * @throws InvalidConfigException
     */
    static protected function mainModel () {
        throw new InvalidConfigException('Define mainModel function');
    }

    /**
     * @returns Search Model class name
     * @throws InvalidConfigException
     */
    static protected function searchModel () {
        throw new InvalidConfigException('Define searchModel function');
    }

    /**
     * @returns Main model's formName()
     */
    static protected function formName () {
        return static::newModel()->formName();
    }

    /**
     * @returns Main model's camel2id'ed formName()
     */
    static protected function idName ($separator='-') {
        return Inflector::camel2id(static::formName(),$separator);
    }

    /**
     * @param array $params - params array the will be used to create the model
     * @returns Model
     */
    static protected function newModel ($params = []) {
        return \Yii::createObject(static::mainModel(), $params);
    }

    /**
     * @param int|array $id
     * @throws NotFoundHttpException
     */
    static protected function findModel ($id) {
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $model = static::newModel()->findOne(is_array($id) ? $id : compact('id'));
        if ($model===null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        };
        return $model;
    }

    static public function renderJson ($data) {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return $data;
    }

    static public function renderJsonp ($data) {
        \Yii::$app->response->format = Response::FORMAT_JSONP;
        return $data;
    }

     public function actionIndex () {
         return $this->render('index');
     }

}
