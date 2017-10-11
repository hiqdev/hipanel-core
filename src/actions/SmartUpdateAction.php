<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

use hipanel\base\Model;
use hipanel\base\SearchModelTrait;
use hiqdev\hiart\ActiveDataProvider;
use Yii;
use yii\web\BadRequestHttpException;

/**
 * Class SmartUpdateAction.
 * @property string view
 */
class SmartUpdateAction extends SwitchAction
{
    const EVENT_BEFORE_FETCH_LOAD = 'beforeFetchLoad';
    const EVENT_BEFORE_FETCH = 'beforeFetch';

    /**
     * @var array|\Closure additional data passed to view
     */
    public $data = [];

    /**
     * @var string The view that represents current update action
     */
    public $_view;

    /**
     * @var mixed ID of object to be viewed. Defaults to `$_GET['id']`
     */
    protected $_id;

    /**
     * @var array additional data passed to model find method
     */
    public $findOptions = [];

    /**
     * @var Model
     */
    private $_searchModel;

    /**
     * @var \yii\data\ActiveDataProvider stores ActiveDataProvider after creating by [[getDataProvider]]
     * @see getDataProvider()
     */
    public $dataProvider;

    /**
     * Creates `ActiveDataProvider` with given options list, stores it to [[dataProvider]].
     * @throws BadRequestHttpException
     * @return ActiveDataProvider
     */
    public function getDataProvider()
    {
        if ($this->dataProvider === null) {
            $this->_id = $this->_id ?: Yii::$app->request->post('selection') ?: Yii::$app->request->get('selection') ?: Yii::$app->request->get('id');

            $this->dataProvider = $this->getSearchModel()->search([], ['pagination' => false]);
            $this->dataProvider->query->andFilterWhere(
                is_array($this->_id) ? ['in', 'id', $this->_id] : ['eq', 'id', $this->_id]
            );

            if (empty($this->dataProvider->query->where)) {
                throw new BadRequestHttpException('Where condition is empty!');
            }

            $this->dataProvider->query->andFilterWhere($this->findOptions)->andWhere(['limit' => 'ALL']);
        }

        return $this->dataProvider;
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
        }
        return $this->_searchModel;
    }

    /** {@inheritdoc} */
    protected function getDefaultRules()
    {
        return array_merge(parent::getDefaultRules(), [
            'POST xeditable' => [
                'class' => XEditableAction::class,
            ],
            'GET html | POST selection' => [
                'class'  => RenderAction::class,
                'data'   => $this->data,
                'view'   => $this->view,
                'params' => function ($action) {
                    $models = $this->fetchModels();
                    foreach ($models as $model) {
                        $model->scenario = $this->scenario;
                    }
                    return [
                        'models' => $models,
                        'model' => reset($models),
                    ];
                },
            ],
            'GET ajax' => [
                'success' => [
                    'class' => RenderAjaxAction::class,
                    'data'   => $this->data,
                    'view'   => $this->view,
                    'params' => function ($action) {
                        $models = $this->fetchModels();
                        foreach ($models as $model) {
                            $model->scenario = $this->scenario;
                        }
                        return [
                            'models' => $models,
                            'model' => reset($models),
                        ];
                    },
                ],
            ],
            'POST html' => [
                'save'    => true,
                'success' => [
                    'class' => RedirectAction::class,
                    'url'   => function ($action) {
                        return $action->collection->count() > 1
                            ? $action->controller->getSearchUrl()
                            : $action->controller->getActionUrl('view', ['id' => $action->collection->first->id]);
                    },
                ],
                'error'   => [
                    'class'  => RenderAction::class,
                    'view'   => $this->view,
                    'data'   => $this->data,
                    'params' => function ($action) {
                        return [
                            'model'  => $action->collection->first,
                            'models' => $action->collection->models,
                        ];
                    },
                ],
            ],
            'POST pjax' => [
                'save'    => true,
                'success' => [
                    'class'  => ProxyAction::class,
                    'action' => 'view',
                    'params' => function ($action, $model) {
                        return ['id' => $model->id];
                    },
                ],
            ],
            'POST ajax' => [
                'save'    => true,
                'success' => [
                    'class' => RedirectAction::class,
                    'url'   => function ($action) {
                        return $action->collection->count() > 1
                            ? $action->controller->getSearchUrl()
                            : $action->controller->getActionUrl('view', ['id' => $action->collection->first->id]);
                    },
                ],
            ],
        ]);
    }

    /**
     * @return string
     */
    public function getView()
    {
        return $this->_view ?: $this->scenario;
    }

    /**
     * @param string $view
     */
    public function setView($view)
    {
        $this->_view = $view;
    }

    /**
     * Fetches models that will be edited.
     *
     * @throws BadRequestHttpException
     * @return array
     */
    public function fetchModels()
    {
        $this->beforeFetchLoad();
        $dataProvider = $this->getDataProvider();
        $this->beforeFetch();
        return $dataProvider->getModels();
    }

    public function beforeFetchLoad()
    {
        $this->trigger(static::EVENT_BEFORE_FETCH_LOAD);
    }

    public function beforeFetch()
    {
        $this->trigger(static::EVENT_BEFORE_FETCH);
    }
}
