<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

use hipanel\base\Model;
use hipanel\base\SearchModelTrait;
use hiqdev\hiart\ActiveDataProvider;
use hiqdev\hiart\ErrorResponseException;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class SearchAction.
 *
 * @var ActiveDataProvider
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

    /**
     * @var \yii\data\ActiveDataProvider stores ActiveDataProvider after creating by [[getDataProvider]]
     * @see getDataProvider()
     */
    public $dataProvider;

    public function init()
    {
        if ($this->ajaxResponseFormatter === null) {
            $this->ajaxResponseFormatter = function ($action) {
                $results = [];

                foreach ($action->collection->models as $k => $v) {
                    $results[$k] = ArrayHelper::toArray($v, $this->returnOptions);
                }

                return $results;
            };
        }

        parent::init();
    }

    /** {@inheritdoc} */
    protected function getDefaultRules()
    {
        return [
            'ajax' => [
                'save' => true,
                'flash' => false,
                'success' => [
                    'class' => RenderJsonAction::class,
                    'return' => $this->ajaxResponseFormatter,
                ],
            ],
        ];
    }

    /**
     * @param $model
     */
    public function setSearchModel($model)
    {
        $this->_searchModel = $model;
    }

    /**
     * @return Model|SearchModelTrait
     */
    public function getSearchModel()
    {
        if (is_null($this->_searchModel)) {
            $this->_searchModel = $this->controller->searchModel();
        } elseif (is_string($this->_searchModel)) {
            $this->_searchModel = Yii::createObject(['class' => $this->_searchModel]);
        } elseif (is_array($this->_searchModel)) {
            $this->_searchModel = Yii::createObject($this->_searchModel);
        }

        return $this->_searchModel;
    }

    /**
     * Creates `ActiveDataProvider` with given options list, stores it to [[dataProvider]].
     *
     * @return ActiveDataProvider
     */
    public function getDataProvider()
    {
        if ($this->dataProvider === null) {
            $formName = $this->getSearchModel()->formName();
            $search = ArrayHelper::merge($this->findOptions, Yii::$app->request->get($formName) ?: Yii::$app->request->get() ?: Yii::$app->request->post());

            $modelClass = $this->getModelClass();
            $this->returnOptions[$modelClass] = ArrayHelper::merge(
                ArrayHelper::remove($search, 'return', []),
                ArrayHelper::remove($search, 'rename', [])
            );

            $this->dataProvider = $this->getSearchModel()->search([$formName => $search], $this->dataProviderOptions);
        }

        return $this->dataProvider;
    }

    /** {@inheritdoc} */
    public function perform()
    {
        $this->beforePerform();

        if (!$this->rule->save) {
            return false;
        }

        $models = [];
        $error = false;

        $dataProvider = $this->getDataProvider();
        try {
            $this->beforeSave();
            $models = $dataProvider->getModels();
        } catch (ErrorResponseException $e) {
            $error = $e->getMessage();
        }

        $this->collection->set($models);

        $this->afterPerform();
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
