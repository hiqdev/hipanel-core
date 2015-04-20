<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;

class Combo2 extends Widget
{
    public static $builtInCombos = [
        'client'    => 'hipanel\modules\client\assets\combo2\Client',
        'reseller'  => 'hipanel\modules\client\assets\combo2\Reseller',
        'server'    => 'hipanel\modules\server\assets\combo2\Server',
        'account'   => 'hipanel\modules\hosting\assets\combo2\Account',
        'service'   => 'hipanel\modules\hosting\assets\combo2\Service',
        'dbService' => 'hipanel\modules\hosting\assets\combo2\DbService',
    ];

    /**
     * @var array the additional options that will be passed to the Combo init JS
     */
    public $fieldOptions = [];

    /**
     * @var string the outer element selector, that holds all of related Combos
     */
    public $formElementSelector = 'form';

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

    /**
     * @var string the language. Default is application language
     */
    public $language;

    /**
     * @var array the params used to create field object
     */
    public $options;

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
    public function registerClientScript () {
        $view = $this->getView();
        $this->registerClientCombo2Config();

        $selector = '#' . Html::getInputId($this->model, $this->attribute);
        $js       = "$('$selector').closest('{$this->formElementSelector}').combo2().register('$selector', '{$this->type}');";

        $view->registerJs($js);
    }

    public function registerClientCombo2Config () {
        $type = $this->type;

        if (isset(static::$builtInCombos[$type])) {
            $type = static::$builtInCombos[$type];
        }
        if (is_array($type)) {
            $options = array_merge($type, $this->options);
        } else {
            $options['class'] = $type;
        }
        if (!$options['class']) d($this);

        return Yii::createObject($options)->register($this->fieldOptions);
    }
}

?>
