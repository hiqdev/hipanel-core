<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\base;

use hipanel\actions\ExportAction;
use hipanel\behaviors\UiOptionsBehavior;
use hipanel\components\Cache;
use hipanel\components\Response;
use hipanel\models\IndexPageUiOptions;
use hiqdev\hiart\ActiveRecord;
use Yii;
use yii\di\Instance;
use yii\helpers\Inflector;
use yii\web\NotFoundHttpException;

/**
 * Site controller.
 */
class Controller extends \yii\web\Controller
{
    /**
     * @var Cache|array|string the cache object or the application component ID of the cache object
     */
    protected $_cache = 'cache';

    /**
     * @var IndexPageUiOptions
     */
    public $indexPageUiOptionsModel;

    public function init()
    {
        parent::init();

        $this->indexPageUiOptionsModel = Yii::createObject(['class' => IndexPageUiOptions::class]);
        $this->indexPageUiOptionsModel->validate(); // In order to get default settings form Model rules()
    }

    public function behaviors()
    {
        return [
            [
                'class' => UiOptionsBehavior::class,
            ],
        ];
    }

    public function actions()
    {
        return [
            'export' => [
                'class' => ExportAction::class,
            ],
        ];
    }

    public function setCache($cache)
    {
        $this->_cache = $cache;
    }

    public function getCache()
    {
        if (!is_object($this->_cache)) {
            $this->_cache = Instance::ensure($this->_cache, Cache::class);
        }

        return $this->_cache;
    }

    /**
     * @var array internal actions
     */
    protected $_internalActions;

    /**
     * @param string $submodel the submodel that will be added to the ClassName
     * @return string Main Model class name
     */
    public static function modelClassName()
    {
        $parts = explode('\\', static::class);
        $last = array_pop($parts);
        array_pop($parts);
        $parts[] = 'models';
        $parts[] = substr($last, 0, -10);

        return implode('\\', $parts);
    }

    /**
     * @param array $config config to be used to create the [[Model]]
     * @return ActiveRecord
     */
    public static function newModel($config = [], $submodel = '')
    {
        $config['class'] = static::modelClassName() . $submodel;

        return Yii::createObject($config);
    }

    /**
     * @param array $config config to be used to create the [[Model]]
     * @return ActiveRecord|SearchModelTrait Search Model object
     */
    public static function searchModel($config = [])
    {
        return static::newModel($config, 'Search');
    }

    /**
     * @return string main model's formName()
     */
    public static function formName()
    {
        return static::newModel()->formName();
    }

    /**
     * @return string search model's formName()
     */
    public static function searchFormName()
    {
        return static::newModel()->formName() . 'Search';
    }

    /**
     * @param string $separator
     * @return string Main model's camel2id'ed formName()
     */
    public static function modelId($separator = '-')
    {
        return Inflector::camel2id(static::formName(), $separator);
    }

    /**
     * Returns the module ID based on the namespace of the controller.
     * @return mixed
     */
    public static function moduleId()
    {
        return array_values(array_slice(explode('\\', get_called_class()), -3, 1, true))[0]; // todo: remove
    }

    public static function controllerId()
    {
        return Inflector::camel2id(substr(end(explode('\\', get_called_class())), 0, -10)); // todo: remove
    }

    /**
     * @param int|array $condition scalar ID or array to be used for searching
     * @param array $config config to be used to create the [[Model]]
     * @throws NotFoundHttpException
     * @return array|ActiveRecord|null|static
     */
    public static function findModel($condition, $config = [])
    {
        /* @noinspection PhpVoidFunctionResultUsedInspection */
        $model = static::newModel($config)->findOne(is_array($condition) ? $condition : ['id' => $condition]);
        if ($model === null) {
            throw new NotFoundHttpException('The requested object not found.');
        }

        return $model;
    }

    public static function findModels($condition, $config = [])
    {
        $containsIntKeys = 0;
        if (is_array($condition)) {
            foreach (array_keys($condition) as $item) {
                if (is_numeric($item)) {
                    $containsIntKeys = true;
                    break;
                }
            }
        }

        if (!is_array($condition) || $containsIntKeys) {
            $condition = ['id' => $condition];
        }
        $models = static::searchModel($config)->search([static::searchFormName() => $condition], ['pagination' => false])->getModels();
        if ($models === null) {
            throw new NotFoundHttpException('The requested object not found.');
        }

        return $models;
    }

    public static function renderJson($data)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        return $data;
    }

    public static function renderJsonp($data)
    {
        Yii::$app->response->format = Response::FORMAT_JSONP;

        return $data;
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function setInternalAction($id, $action)
    {
        $this->_internalActions[$id] = $action;
    }

    public function hasInternalAction($id)
    {
        return array_key_exists($id, $this->_internalActions);
    }

    public function createAction($id)
    {
        $config = $this->_internalActions[$id];

        return $config ? Yii::createObject($config, [$id, $this]) : parent::createAction($id);
    }

    /**
     * Prepares array for building url to action based on given action id and parameters.
     *
     * @param string $action action id
     * @param string|int|array $params ID of object to be action'ed or array of parameters
     * @return array array suitable for Url::to
     */
    public static function getActionUrl($action = 'index', $params = [])
    {
        $params = is_array($params) ? $params : ['id' => $params];

        return array_merge([implode('/', ['', static::moduleId(), static::controllerId(), $action])], $params);
    }

    /**
     * Prepares array for building url to search with given filters.
     */
    public static function getSearchUrl(array $params = [])
    {
        return static::getActionUrl('index', [static::searchFormName() => $params]);
    }
}
