<?php

namespace hipanel\behaviors;

use hipanel\models\IndexPageUiOptions;
use Yii;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\web\Controller;

class UiOptionsBehavior extends Behavior
{
    /**
     * @var mixed
     */
    public $modelClass;

    /**
     * @var array
     */
    public $allowedRoutes = [];

    /**
     * @var IndexPageUiOptions
     */
    private $_model;

    public function init()
    {
        parent::init();

        foreach ($this->allowedRoutes as &$allowedRoute) {
            $allowedRoute = ltrim(Yii::getAlias($allowedRoute), '/');
        }
    }

    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'ensureOptions'];
    }

    public function ensureOptions($event)
    {
        if ($this->isRouteAllowed($this->getRoute())) {
            $options = [];
            $params = Yii::$app->request->get();
            if ($params) {
                $model = $this->getModel();
                foreach ($params as $key => $value) {
                    if (in_array($key, array_keys($model->toArray()))) {
                        $options[$key] = $value;
                    }
                }
                $model->attributes = $options;
                if ($model->validate()) {
                    $this->getUiOptionsStorage()->set($this->getRoute(), $model->toArray());
                } else {
                    $errors = $model->getErrors();
                }
            }
        }
    }

    protected function getModel()
    {
        if ($this->_model === null) {
            $this->_model = $this->findModel();
        }

        return $this->_model;
    }

    protected function findModel()
    {
        if (isset($this->modelClass['class']) && class_exists($this->modelClass['class'])) {
            return Yii::createObject($this->modelClass);
        } else {
            throw new InvalidConfigException('UiOptionsBehavior::$modelClass must contain `class` item');
        }
    }

    protected function isRouteAllowed($route)
    {
        return in_array($route, $this->allowedRoutes, true);
    }

    protected function getUiOptionsStorage()
    {
        return Yii::$app->get('uiOptionsStorage');
    }

    protected function getRoute()
    {
        return Yii::$app->request->pathInfo;
    }
}
