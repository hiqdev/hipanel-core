<?php

namespace hipanel\actions;

use Closure;
use Yii;

/**
 * Class IndexAction
 */
class IndexAction extends Action
{
    /**
     * @var array|Closure additional data passed to view
     */
    public $data = [];

    public $_model;

    public function setModel($model)
    {
        $this->_model = $model;
    }

    public function getModel()
    {
        if (is_null($this->_model)) {
            $this->_model = $this->controller->searchModel();
        }
        return $this->_model;
    }

    public function getDataProvider()
    {
        return $this->getModel()->search(Yii::$app->request->queryParams);
    }

    public function prepareData()
    {
        if ($this->data instanceof Closure) {
            return call_user_func($this->data, $this);
        } else {
            return $this->data;
        }
    }

    public function run()
    {
        return $this->controller->render('index', array_merge([
            'model'        => $this->getModel(),
            'dataProvider' => $this->getDataProvider(),
        ], $this->prepareData()));
    }

}
