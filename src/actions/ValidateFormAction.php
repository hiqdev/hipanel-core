<?php

namespace hipanel\actions;

use hipanel\base\Model;
use Yii;
use hiqdev\hiart\Collection;
use yii\base\InvalidRouteException;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class ValidateFormAction
 */
class ValidateFormAction extends Action
{
    public $allowDynamicScenario = true;

    protected $model;

    public function getModel($options = [])
    {
        if ($this->model === null) {
            return $this->controller->newModel(array_merge([
                'scenario' => $this->scenario
            ], $options));
        } else {
            return Yii::createObject(ArrayHelper::merge(['scenario' => $this->getScenario()], $this->model));
        }
    }

    public function createCollection(Model $model)
    {
        return Yii::createObject([
            'class' => Collection::className(),
            'model' => $model,
        ]);
    }

    public function run($scenario = null)
    {
        if ($scenario && $this->allowDynamicScenario) {
            $this->scenario = $scenario;
        }

        if (Yii::$app->request->isPost) {
            $collection = $this->createCollection($this->getModel());
            $collection->load();
            return $this->controller->renderJson(ActiveForm::validateMultiple($collection->models));
        }

        throw new InvalidRouteException('Must be POST request');
    }

    /**
     * @param mixed $model
     */
    public function setModel($model)
    {
        if ($model instanceof Model) {
            $this->model = $model;
        } elseif ($model instanceof \Closure) {
            $this->model = call_user_func($model, $this);
        } else {
            $this->model = is_array($model) ? $model : ['class' => $model];;
        }
    }
}
