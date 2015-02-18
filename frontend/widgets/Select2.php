<?php
namespace frontend\widgets;

use frontend\assets\Select2Asset;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

class Select2 extends Widget
{

    public $settings = [];

    public $language;

    public $model;

    public $options;

    public $attribute;

    public $value;

    public $url;

    private function _defaultSettings () {
        return [
            'allowClear'         => true,
            'placeholder'        => Yii::t('app', 'Type here ...'),
            'width'              => '100%',
            'triggerChange'      => true,
            'minimumInputLength' => 3,
            'ajax'               => [
                'url'      => $this->url,
                'dataType' => 'json',
                'data'     => new JsExpression('function(term,page) { return {search:term}; }'),
                'results'  => new JsExpression('function(data,page) { return {results:data.results}; }'),
            ],
            'initSelection'      => new JsExpression('function (elem, callback) {
                var id=$(elem).val();
                $.ajax("' . $this->url . '?id=" + id, {
                    dataType: "json"
                }).done(function(data) {
                    callback(data.results);
                });
            }')
        ];
    }

    /**
     * @inheritdoc
     */
    public function init () {
        parent::init();
        $this->_initOptions();
        $this->_initSettings();
        $this->registerClientScript();

        // Set language
        if ($this->language === null && ($language = Yii::$app->language) !== 'en-US') {
            $this->language = substr($language, 0, 2);
        }
    }

    /**
     * @inheritdoc
     */
    public function run () {
        $this->registerClientScript();

        return Html::activeTextInput($this->model, $this->attribute, $this->options);
        // return Html::textInput($this->attribute, $_GET[$this->attribute], $this->options);

    }

    /**
     * Register widget asset.
     */
    public function registerClientScript() {
        $view = $this->getView();

        $selector = '#' . $this->options['id'];
        $settings = Json::encode($this->settings);
        // Register asset
        $asset = Select2Asset::register($view);

        // Init widget
        $view->registerJs(new JsExpression("$('$selector').select2($settings);"), \yii\web\View::POS_READY);
    }

    private function _initOptions () {
        $this->options['id'] = $this->attribute;
    }

    private function _initSettings () {
        $this->settings = ArrayHelper::merge($this->_defaultSettings(), $this->settings);
    }
}

?>
