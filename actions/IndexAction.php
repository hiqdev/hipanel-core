<?php

namespace hipanel\actions;

use Closure;
use hipanel\base\Model;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class IndexAction
 *
 * @property Model model
 */
class IndexAction extends Action
{
    /**
     * @var string view to render.
     */
    public $view = 'index';

    /**
     * @var array|Closure additional data passed to view
     */
    public $data = [];

    /**
     * @var array additional data that will be passed to the search query
     */
    public $findOptions = [];

    /**
     * @var Model
     */
    public $_model;

    public function setModel($model)
    {
        $this->_model = $model;
    }

    /**
     * @return Model
     */
    public function getModel()
    {
        if (is_null($this->_model)) {
            $this->_model = $this->controller->searchModel();
        }
        return $this->_model;
    }

    public function getDataProvider()
    {
        $params            = Yii::$app->request->queryParams;
        $formName          = $this->getModel()->formName();
        $params[$formName] = ArrayHelper::merge($params[$formName], $this->findOptions);
        return $this->getModel()->search($params, ['pagination' => ['pageSize' => Yii::$app->request->get('per_page') ? : 25]]);
    }

    public function prepareData()
    {
        if ($this->data instanceof Closure) {
            return call_user_func($this->data, $this);
        } else {
            return (array)$this->data;
        }
    }

    public function run()
    {
        return $this->controller->render($this->view, array_merge([
            'model'        => $this->getModel(),
            'dataProvider' => $this->getDataProvider(),
        ], $this->prepareData()));
    }

}
