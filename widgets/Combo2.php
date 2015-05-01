<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use Yii;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;
use hipanel\helpers\ArrayHelper;

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
     * @var array the additional JS options that will be passed directly to the Combo init JS
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
     * @var array the HTML options for the input element
     */
    public $inputOptions;

    /**
     * @var string the language. Default is application language
     */
    public $language;

    /**
     * @var array the params used to create field object
     */
    public $options = [];

    /**
     * @var Combo2Config the Combo2 that will be created
     */
    public $combo2;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // Set language
        if ($this->language === null && ($language = Yii::$app->language) !== 'en-US') {
            $this->language = substr($language, 0, 2);
        }

        $config = $this->options;

        if (isset(static::$builtInCombos[$this->type])) {
            $config['class'] = static::$builtInCombos[$this->type];
        } else {
            $config['class'] = $this->type;
        }
        $this->combo2 = \Yii::createObject($config);
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->registerClientScript();

        return Html::activeTextInput($this->model, $this->attribute, $this->inputOptions);
    }

    /**
     * Register widget asset.
     */
    public function registerClientScript()
    {
        $view      = $this->getView();
        $config_id = $this->registerClientCombo2();
        $selector  = '#' . Html::getInputId($this->model, $this->attribute);
        $js        = "$('$selector').closest('{$this->formElementSelector}').combo2().register('$selector', '$config_id');";

        $view->registerJs($js);
    }

    public function registerClientCombo2()
    {
        return $this->combo2->register($this->getFieldOptions());
    }

    public function getFieldOptions()
    {
        return ArrayHelper::merge([
            'hasId' => substr($this->attribute, -3) == '_id'
        ], $this->fieldOptions);
    }

}

?>
