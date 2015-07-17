<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use hipanel\base\Model;
use Yii;
use hiqdev\hiart\Collection;
use yii\base\InvalidRouteException;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;

/**
 * Class FormValidateAction
 * @package hipanel\actions
 *
 * @property
 */
class FormValidateAction extends \yii\base\Action
{
    /**
     * @var \hipanel\base\CrudController|\yii\web\Controller the controller that owns this action
     */
    public $controller;

    public $allowDynamicScenario = true;

    public $scenario;


    public function resolveScenario()
    {
        return $this->scenario ?: $this->id;
    }

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
        if ($scenario !== null && $this->allowDynamicScenario) {
            $this->scenario = $scenario;
        } else {
            $this->scenario = $this->resolveScenario();
        }

        if (Yii::$app->request->isPost) {
            $collection = $this->createCollection($this->getModel());
            $collection->load();
            return $this->controller->renderJson(ActiveForm::validateMultiple($collection->models));
        }

        throw new InvalidRouteException('Unable to run this action with such request type');
    }
}
