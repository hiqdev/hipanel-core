<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\base;

use Yii;
use hiqdev\hiar\ActiveRecord;
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
     * @var array internal actions.
     */
    protected $_internalActions;

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
     * @param string $submodel the submodel that will be added to the ClassName
     * @return string Main Model class name
     */
    static public function modelClassName () {
        $parts = explode('\\', static::className());
        $last  = array_pop($parts);
        array_pop($parts);
        $parts[] = 'models';
        $parts[] = substr($last, 0, -10);

        return implode('\\', $parts);
    }

    /**
     * @param array $config config to be used to create the [[Model]]
     * @returns ActiveRecord
     */
    static public function newModel ($config = [], $submodel = '') {
        return \Yii::createObject(static::modelClassName().$submodel, $config);
    }

    /**
     * @param array $config config to be used to create the [[Model]]
     * @returns ActiveRecord Search Model object
     */
    static public function searchModel ($config = []) {
        return static::newModel($config, 'Search');
    }

    /**
     * @returns string Main model's formName()
     */
    static public function formName () {
        return static::newModel()->formName();
    }

    /**
     * @param string $separator
     * @return string Main model's camel2id'ed formName()
     */
    static public function idName ($separator = '-') {
        return Inflector::camel2id(static::formName(), $separator);
    }

    /**
     * @param int|array $condition scalar ID or array to be used for searching
     * @param array $config config to be used to create the [[Model]]
     * @return array|ActiveRecord|null|static
     * @throws NotFoundHttpException
     */
    static public function findModel ($condition, $config = []) {
        /** @noinspection PhpVoidFunctionResultUsedInspection */
        $model = static::newModel($config)->findOne(is_array($condition) ? $condition : ['id'=>$condition]);
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

    public function setInternalAction($id, $action)
    {
        $this->_internalActions[$id] = $action;
    }

    public function createAction($id)
    {
        $config = $this->_internalActions[$id];
        return $config ? Yii::createObject($config, [$id, $this]) : parent::createAction($id);
    }

}
