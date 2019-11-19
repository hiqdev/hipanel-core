<?php


namespace hipanel\widgets;


use hipanel\assets\AssignAttributesAsset;
use yii\base\Widget;

/**
 * Class AssignAttributesWidget
 * @package hipanel\widgets
 */
class AssignAttributesWidget extends Widget
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
     * @var \yii\web\View
     */
    public $view;

    /**
     * @inheritDoc
     */
    public function run()
    {
        AssignAttributesAsset::register($this->view);
        $options = \yii\helpers\Json::htmlEncode([
            'countModels' => count($this->models),
            'formName' => strtolower(reset($this->models)->formName()),
            'attributes' => $this->attributes,
            'linkText' => \Yii::t('hipanel', 'Apply to all'),
        ]);
        $this->view->registerJs("\$('#assign-hubs-form').assignHubs($options);");

        return parent::run();
    }
}
