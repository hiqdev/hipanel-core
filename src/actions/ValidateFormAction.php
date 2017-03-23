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
use Yii;
use yii\base\InvalidRouteException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class ValidateFormAction.
 */
class ValidateFormAction extends Action
{
    public $allowDynamicScenario = true;

    /**
     * @var \Closure|false function used to generate the input ID for the validated field.
     * Method signature: `function ($action, $model, $id, $attribute, $errors)`
     * Closure MUST return string.
     *
     * Set this property value to `false`, if input ID must not contain sequential index
     */
    public $validatedInputId = null;

    public function init()
    {
        $scenario = Yii::$app->request->get('scenario');

        if ($scenario !== null && $this->allowDynamicScenario) {
            $this->scenario = $scenario;
        }
    }

    public function getModel($options = [])
    {
        if ($this->model === null) {
            return $this->controller->newModel(array_merge([
                'scenario' => $this->scenario,
            ], $options));
        } else {
            return Yii::createObject(ArrayHelper::merge(['scenario' => $this->getScenario()], $this->model));
        }
    }

    public function run()
    {
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
            /* @var Model $model */
            $model->validate();
            foreach ($model->getErrors() as $attribute => $errors) {
                if ($this->validatedInputId instanceof \Closure) {
                    $id = call_user_func($this->validatedInputId, $this, $model, $i, $attribute, $errors);
                } elseif ($this->validatedInputId === false) {
                    $id = Html::getInputId($model, $attribute);
                } else {
                    $id = Html::getInputId($model, "[$i]" . $attribute);
                }
                $result[$id] = $errors;
            }
        }
        return $result;
    }

    /**
     * Sets the $model in the [[collection]].
     *
     * @param Model $model
     */
    public function setModel($model)
    {
        $this->collection->setModel($model);
    }
}
