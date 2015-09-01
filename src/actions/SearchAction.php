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
     * @var boolean Whether to use pagination in search
     */
    public $pagination = true;

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
    public $_searchModel;

    public function init()
    {
        parent::init();

        if (!isset($this->dataProviderOptions['pagination'])) {
            $this->dataProviderOptions['pagination'] = false;
        }

        $this->addItems([
            'ajax' => [
                'save' => true,
                'flash' => false,
                'success' => [
                    'class' => 'hipanel\actions\RenderJsonAction',
                    'return' => function ($action) {
                        return $action->collection->models;
                    }
                ]
            ]
        ]);
    }

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
        $search = ArrayHelper::merge($this->findOptions, Yii::$app->request->get($formName) ?: Yii::$app->request->post());

        $this->returnOptions[$this->controller->modelClassName()] = ArrayHelper::merge(
            ArrayHelper::remove($search, 'return'),
            ArrayHelper::remove($search, 'rename')
        );

        return $this->getSearchModel()->search([$formName => $search], $this->dataProviderOptions);
    }

    /** @inheritdoc */
    public function perform()
    {
        if (!$this->rule->save) {
            return false;
        }

        $results = [];
        $error = false;

        try {
            $results = $this->getDataProvider()->getModels();
        } catch (ErrorResponseException $e) {
            $error = $e->getMessage();
        }

        foreach ($results as $k => $v) {
            $results[$k] = ArrayHelper::toArray($v, $this->returnOptions);
        }

        $this->collection->load($results);

        return $error;
    }
}
