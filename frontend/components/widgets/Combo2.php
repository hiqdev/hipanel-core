<?php
namespace frontend\components\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;

class Combo2 extends Widget
{
    public static $builtInCombos = [
        'client' => '\frontend\modules\client\assets\combo2\Client',
        'server' => '\frontend\modules\server\assets\combo2\Server',
    ];

    /**
     * @var array the additional options that will be passed to the Combo init JS
     */
    public $clientOptions = [];

    /**
     * @var Model
     */
    public $model;

    /**
     * @var string the attribute name
     */
    public $attribute;

    /**
     * @var string the type of the Combo
     */
    public $type;

    /**
     * @var array the options for the input
     */
    public $inputOptions;

    public $language;

    /**
     * @inheritdoc
     */
    public function init () {
        parent::init();

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

        return Html::activeTextInput($this->model, $this->attribute, $this->inputOptions);
    }

    /**
     * Register widget asset.
     */
    public function registerClientScript() {
        $view = $this->getView();
        $this->registerClientCombo2Config();

        $selector = '#' . Html::getInputId($this->model, $this->attribute);
        $view->registerJs("$('$selector').closest('form').combo2().register('$selector', '{$this->type}');");
    }

    public function registerClientCombo2Config() {
        if (!empty(static::$builtInCombos[$this->type])) {
            Yii::createObject(static::$builtInCombos[$this->type])->register($this->clientOptions);
        } else {
            throw new InvalidConfigException('The combo2 type is not registered');
        }
    }
}

?>
