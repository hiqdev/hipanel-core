<?php


namespace hipanel\widgets;


use hipanel\assets\ApplyToAllAsset;
use yii\base\Widget;
use yii\helpers\Inflector;

/**
 * Class AssignAttributesWidget
 * @package hipanel\widgets
 */
class ApplyToAllWidget extends Widget
{
    /**
     * @var \hipanel\base\Model[]
     */
    public $models;

    /**
     * @var string[]
     */
    public $attributes;

    /**
     * @inheritDoc
     */
    public function run()
    {
        ApplyToAllAsset::register($this->view);
        $commonFormName = reset($this->models)->formName();
        $formId = Inflector::camel2id($commonFormName);
        $formName = strtolower($commonFormName);
        $options = \yii\helpers\Json::htmlEncode([
            'formId' => $formId,
            'formName' => $formName,
            'attributes' => $this->attributes,
            'linkText' => \Yii::t('hipanel', 'Apply to all'),
        ]);
        $this->view->registerJs("\$('#$formId').applyToAll($options);");

        return parent::run();
    }
}
