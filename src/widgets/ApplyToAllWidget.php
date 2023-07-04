<?php
declare(strict_types=1);

namespace hipanel\widgets;

use hipanel\assets\ApplyToAllAsset;
use Yii;
use yii\base\Widget;
use yii\helpers\Inflector;
use yii\helpers\Json;

class ApplyToAllWidget extends Widget
{
    public array $models = [];
    public array $attributes = [];

    public function run()
    {
        ApplyToAllAsset::register($this->view);
        $commonFormName = reset($this->models)->formName();
        $formId = Inflector::camel2id($commonFormName);
        $formName = strtolower($commonFormName);
        $options = Json::htmlEncode([
            'formId' => $formId,
            'formName' => $formName,
            'attributes' => $this->attributes,
            'linkText' => Yii::t('hipanel', 'Apply to all'),
        ]);
        $this->view->registerJs("\$('#$formId').applyToAll($options);");

        return parent::run();
    }
}
