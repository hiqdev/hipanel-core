<?php

namespace hipanel\actions;

use Yii;
use hipanel\base\Model;
use hipanel\base\SearchModelTrait;
use hiqdev\hiart\ActiveDataProvider;
use yii\web\BadRequestHttpException;

/**
 * Class SmartUpdateAction
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
     * Creates `ActiveDataProvider` with given options list, stores it to [[dataProvider]]
     * @return ActiveDataProvider
     * @throws BadRequestHttpException
     */
    public function getDataProvider()
    {
        if ($this->dataProvider === null) {
            $this->_id = $this->_id ?: Yii::$app->request->post('selection') ?: Yii::$app->request->get('selection') ?: Yii::$app->request->get('id');

            $this->dataProvider = $this->getSearchModel()->search([], ['pagination' => false]);
            $this->dataProvider->query->andFilterWhere([
                'id' => !is_array($this->_id) ? $this->_id : null,
                'id_in' => is_array($this->_id) ? $this->_id : null,
            ]);

            if (!isset($this->dataProvider->query->where['id']) && !isset($this->dataProvider->query->where['id_in'])) {
                throw new BadRequestHttpException('ID is missing');
            }

            $this->dataProvider->query->andFilterWhere($this->findOptions);
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

    public function init()
    {
        parent::init();
        $this->addItems([
            'POST xeditable' => [
                'class' => 'hipanel\actions\XEditableAction',
            ],
            'GET | POST selection' => [
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
                        'model' => reset($models)
                    ];
                },
            ],
            'POST html' => [
                'save'    => true,
                'success' => [
                    'class' => RedirectAction::class,
                    'url'   => function ($action) {
                        return count($action->collection->count()) > 1
                            ? $action->controller->getSearchUrl(['ids' => $action->collection->ids])
                            : $action->controller->getActionUrl('view', ['id' => $action->collection->first->id])
                        ;
                    }
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
                    }
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
                ]
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
     * Fetches models that will be edited
     *
     * @return array
     * @throws BadRequestHttpException
     */
    public function fetchModels() {
        $this->beforeFetchLoad();
        $dataProvider = $this->getDataProvider();
        $this->beforeFetch();
        return $dataProvider->getModels();
    }

    public function beforeFetchLoad() {
        $this->trigger(static::EVENT_BEFORE_FETCH_LOAD);
    }

    public function beforeFetch() {
        $this->trigger(static::EVENT_BEFORE_FETCH);
    }
}
