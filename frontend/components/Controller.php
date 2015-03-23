<?php

namespace frontend\components;

use frontend\components\hiresource\ActiveRecord;
use yii\base\InvalidConfigException;
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
    public function behaviors () {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['index'],
                'rules' => [
                    [
                        'actions' => ['index'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param string $postfix the postfix that will be added to the ClassName
     * @return string Main Model class name
     */
    static protected function modelClassName ($postfix = '') {
        $parts = explode('\\', static::className());
        $last  = array_pop($parts);
        array_pop($parts);

        return implode('\\', $parts) . '\\models\\' . substr($last, 0, -10) . $postfix;
    }

    /**
     * @param array $config config to be used to create the [[Model]]
     * @returns ActiveRecord Search Model object
     */
    static protected function searchModel ($config = []) {
        return \Yii::createObject(static::modelClassName('Search'), $config);
    }

    /**
     * @param array $config config to be used to create the [[Model]]
     * @returns ActiveRecord
     */
    static protected function newModel ($config = []) {
        return \Yii::createObject(static::modelClassName(), $config);
    }

    /**
     * @returns string Main model's formName()
     */
    static protected function formName () {
        return static::newModel()->formName();
    }

    /**
     * @param string $separator
     * @return string Main model's camel2id'ed formName()
     */
    static protected function idName ($separator = '-') {
        return Inflector::camel2id(static::formName(), $separator);
    }

    /**
     * @param int|array $id scalar ID or array to be used for searching
     * @param array $config config to be used to create the [[Model]]
     * @return array|ActiveRecord|null|static
     * @throws NotFoundHttpException
     */
    static protected function findModel ($id, $config = []) {
        if (isset($id['scenario'])) $scenario = ArrayHelper::remove($id, 'scenario');
        if (!isset($config['scenario'])) $config['scenario'] = $scenario;
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $model = static::newModel($config)->findOne(is_array($id) ? $id : compact('id'));
        if ($model === null) {
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
