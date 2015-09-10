<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use hipanel\base\Model;
use hiqdev\hiart\ActiveDataProvider;
use Yii;
use hiqdev\hiart\ErrorResponseException;
use yii\helpers\ArrayHelper;

/**
 * Class SearchAction
 *
 * @package hipanel\actions
 */
class SearchAction extends SwitchAction
{
    /**
     * @var array Options that will be passed to SearchModel by default
     */
    public $findOptions = [];

    /**
     * @var array Options that will be passed to [[SearchModel::search()]] method as second argument
     * and will be stored in [[DataProvider]]
     */
    public $dataProviderOptions = [];

    /**
     * @var array Options that will be returned. Used only for ajax rendering rules
     */
    protected $returnOptions = [];

    /**
     * @var Model
     */
    private $_searchModel;

    /**
     * @var callback Function to collect the response for ajax request/
     * @param SwitchAction $action
     * @returns array
     */
    public $ajaxResponseFormatter;

    public function init()
    {
        parent::init();

        if (!isset($this->dataProviderOptions['pagination'])) {
            $this->dataProviderOptions['pagination'] = false;
        }

        if ($this->ajaxResponseFormatter === null) {
            $this->ajaxResponseFormatter = function ($action) {
                $results = [];

                foreach ($action->collection->models as $k => $v) {
                    $results[$k] = ArrayHelper::toArray($v, $this->returnOptions);
                }

                return $results;
            };
        }

        $this->addItems([
            'ajax' => [
                'save' => true,
                'flash' => false,
                'success' => [
                    'class' => 'hipanel\actions\RenderJsonAction',
                    'return' => $this->ajaxResponseFormatter
                ]
            ]
        ]);
    }

    /**
     * @param $model
     */
    public function setSearchModel($model)
    {
        $this->_searchModel = $model;
    }

    /**
     * @return Model
     */
    public function getSearchModel()
    {
        if (is_null($this->_searchModel)) {
            $this->_searchModel = $this->controller->searchModel();
        }
        return $this->_searchModel;
    }

    /**
     * Prepares DataProvider with given options list
     *
     * @return ActiveDataProvider
     */
    public function getDataProvider()
    {
        $formName = $this->getSearchModel()->formName();
        $search = ArrayHelper::merge($this->findOptions, Yii::$app->request->get($formName) ?: Yii::$app->request->get() ?: Yii::$app->request->post());

        $this->returnOptions[$this->controller->modelClassName()] = ArrayHelper::merge(
            ArrayHelper::remove($search, 'return', []),
            ArrayHelper::remove($search, 'rename', [])
        );

        return $this->getSearchModel()->search([$formName => $search], $this->dataProviderOptions);
    }

    /** @inheritdoc */
    public function perform()
    {
        if (!$this->rule->save) {
            return false;
        }

        $models = [];
        $error = false;

        try {
            $models = $this->getDataProvider()->getModels();
        } catch (ErrorResponseException $e) {
            $error = $e->getMessage();
        }

        $this->collection->set($models);

        return $error;
    }

    /**
     * @return array
     */
    public function getReturnOptions()
    {
        return $this->returnOptions;
    }
}
