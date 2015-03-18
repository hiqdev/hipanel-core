<?php

namespace frontend\components;

use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\helpers\Inflector;
use yii\helpers\ArrayHelper;
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
     * @returns string Main Model class name
     */
    static protected function mainModel ($postfix='') {
        $parts = explode('\\',static::className());
        $last  = array_pop($parts);
        array_pop($parts);
        return implode('\\',$parts).'\\models\\'.substr($last,0,-10).$postfix;
    }

    /**
     * @returns string Search Model class name
     */
    static protected function searchModel () {
        return static::mainModel('Search');
    }

    /**
     * @param array $config config to be used to create the [[Model]]
     * @returns Model
     */
    static protected function newModel ($config = []) {
        return \Yii::createObject(static::mainModel(), $config);
    }

    /**
     * @returns string Main model's formName()
     */
    static protected function formName () {
        return static::newModel()->formName();
    }

    /**
     * @returns string Main model's camel2id'ed formName()
     */
    static protected function idName ($separator='-') {
        return Inflector::camel2id(static::formName(),$separator);
    }

    /**
     * @param int|array $id scalar ID or array to be used for searching
     * @param array $config config to be used to create the [[Model]]
     * @throws NotFoundHttpException
     */
    static protected function findModel ($id,$config=[]) {
        if (isset($id['scenario'])) $scenario = ArrayHelper::remove($id,'scenario');
        if (!isset($config['scenario'])) $config['scenario'] = $scenario;
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $model = static::newModel($config)->findOne(is_array($id) ? $id : compact('id'));
        if ($model===null) {
            throw new NotFoundHttpException('The requested object not found.');
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
