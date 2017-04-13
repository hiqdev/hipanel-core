<?php

namespace hipanel\behaviors;

use hipanel\components\UiOptionsStorage;
use hipanel\models\IndexPageUiOptions;
use Yii;
use yii\base\Behavior;
use yii\helpers\Inflector;
use yii\web\Controller;

class UiOptionsBehavior extends Behavior
{
    /**
     * @var mixed
     */
    public $modelClass;

    /**
     * @var IndexPageUiOptions
     */
    private $_model;

    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'ensureUiOptions'];
    }

    public function ensureUiOptions($event)
    {
        if ($this->isRouteAllowed()) {
            $options = [];
            $params = Yii::$app->request->get();
            $model = $this->getModel();
            $model->attributes = $this->getUiOptionsStorage()->get($this->getRoute());;
            $model->availableRepresentations = $this->findRepresentations();
            if ($params) {
                foreach ($params as $key => $value) {
                    if (in_array($key, array_keys($model->toArray()))) {
                        $options[$key] = $value;
                    }
                }
                $model->attributes = $options;
                if ($model->validate()) {
                    $this->getUiOptionsStorage()->set($this->getRoute(), $model->toArray());
                } else {
                    $errors = json_encode($model->getErrors());
                    Yii::warning('UiOptionsBehavior - IndexPageUiModel validation errors: ' . $errors);
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
        return $this->owner->indexPageUiOptionsModel;
    }

    protected function isRouteAllowed()
    {
        return $this->owner->action->id === 'index';
    }

    /**
     * @return UiOptionsStorage
     */
    protected function getUiOptionsStorage()
    {
        return Yii::$app->get('uiOptionsStorage');
    }

    protected function getRoute()
    {
        return Yii::$app->request->pathInfo;
    }

    protected function findRepresentations()
    {
        $out = [];
        $module = $this->owner->module->id;
        $owner = Inflector::id2camel($this->owner->id);
        $gridClass =  sprintf('\hipanel\modules\%s\grid\%sGridView', $module, $owner);
        if (class_exists($gridClass)) {
            $out = array_keys($gridClass::getRepresentations());
        }

        return $out;
    }
}
