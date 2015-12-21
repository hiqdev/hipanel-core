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

    /**
     * @var \Closure function used to generate the input ID for the validated field.
     * Method signature: `function ($action, $model, $id, $attribute, $errors)`
     * Closure MUST return string.
     */
    public $validatedInputId = null;

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

    public function run($scenario = null)
    {
        if ($scenario && $this->allowDynamicScenario) {
            $this->scenario = $scenario;
        }

        if (Yii::$app->request->isPost) {
            $this->loadCollection();
            return $this->controller->renderJson($this->validateMultiple());
        }

        throw new InvalidRouteException('Must be POST request');
    }

    public function validateMultiple()
    {
        $result = [];
        foreach ($this->collection->models as $i => $model) {
            /** @var Model $model */
            $model->validate();
            foreach ($model->getErrors() as $attribute => $errors) {
                if ($this->validatedInputId instanceof \Closure) {
                    $id = call_user_func($this->validatedInputId, $this, $model, $i, $attribute, $errors);
                } else {
                    $id = Html::getInputId($model, "[$i]" . $attribute);
                }
                $result[$id] = $errors;
            }
        }
        return $result;
    }

    /**
     * Sets the $model in the [[collection]]
     *
     * @param Model $model
     */
    public function setModel($model) {
        $this->collection->setModel($model);
    }
}
