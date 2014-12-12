<?php
namespace frontend\widgets;

use yii\base\Widget;
use yii\helpers\Html;

class Select2 extends Widget
{

    public $settings = [];

    public $language;

    public $model;

    public $options;

    public $attribute;

    public $value;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerClientScript();
        // Set language
//        if ($this->language === null && ($language = Yii::$app->language) !== 'en-US') {
//            $this->language = substr($language, 0, 2);
//        }
    }
    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();
        return Html::activeTextInput($this->model, $this->attribute, $this->options);
        // return Html::textInput($this->attribute, $_GET[$this->attribute], $this->options);

    }
    /**
     * Register widget asset.
     */
    public function registerClientScript()
    {
        $view = $this->getView();

        $selector = '#' . $this->options['id'];
        $settings = \yii\helpers\Json::encode($this->settings);
        // Register asset
        $asset = \frontend\assets\Select2Asset::register($view);

        // Init widget
        $view->registerJs("jQuery('$selector').select2($settings);");
    }
}
?>
