<?php

namespace frontend\components;

use frontend\assets\Combo2Asset;
use frontend\components\helpers\ArrayHelper;
use yii\base\Object;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

class Combo2Config extends Object
{
    /**
     * @var array the url that will be passed to [[Url::to()]] method to create the request URL
     */
    public $url;

    /**
     * @var string the type of the field
     */
    public $type;

    /** @inheritdoc */
    public function init () {
        if (!$this->url) {
            $this->url = '/' . implode('/', [$this->type, $this->type, 'search']);
        }
    }

    /**
     * Returns the config of the Combo2, merges with the passed $config
     *
     * @param array $config
     * @return array
     */
    public function getConfig ($config = []) {
        return ArrayHelper::merge([
            'name'          => $this->type,
            'type'          => $this->type,
            'pluginOptions' => [
                'width'       => '100%',
                'placeholder' => \Yii::t('app', 'Start typing here'),
                'ajax'        => [
                    'url'  => Url::toRoute($this->url),
                    'type' => 'POST',
                    'data' => new JsExpression("
                        function (term) {
                            return $(this).data('field').form.createFilter({
                                '{$this->type}_like': {format: term}
                            });
                        }
                    ")
                ]
            ]
        ], $config);
    }

    /**
     * Registers the Combo2 config in the view
     *
     * @param array $config
     * @return bool
     */
    public function register ($config = []) {
        $config_json = Json::encode(static::getConfig($config));
        $view        = \Yii::$app->getView();
        Combo2Asset::register($view);
        $view->registerJs("$.fn.combo2Config().add('{$this->type}', $config_json);", View::POS_READY, 'combo2Config_' . $this->type);

        return true;
    }
}