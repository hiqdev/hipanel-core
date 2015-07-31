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

    public function getModel($options = [])
    {
        return $this->controller->newModel(array_merge([
            'scenario' => $this->scenario
        ], $options));
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
}
