<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use hipanel\base\View;
use hipanel\helpers\ArrayHelper;
use yii\base\Model;
use yii\base\Object;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;
use Yii;

/**
 * Widget Combo.
 *
 * @property mixed $return see [[_return]]
 * @property mixed $rename see [[_rename]]
 * @property mixed $filter see [[_filter]]
 * @property mixed $pluginOptions see [[_pluginOptions]]
 * @property mixed $primaryFilter see [[_primaryFilter]]
 */
class Combo extends Widget
{
    /**
     * @var Model
     */
    public $model;

    /**
     * @var string the attribute name
     */
    public $attribute;

    /**
     * @var array the url that will be passed to [[Url::to()]] method to create the request URL
     */
    public $url;

    /**
     * @var string the type of the field.
     * Usual should be module/comboName.
     * For example: client/client, hosting/account, domain/domain.
     * In case of the combo overriding with some specific filters,
     * the type should represent the filter.
     * For example: if the hosting/service combo is extended with filter
     * to show only DB services, the type should be hosting/service/db or hosting/dbService.
     * The decision of the style depends on overall code style and readability.
     */
    public $type;

    /**
     * @var string the name of the representative field in the model.
     * Used by [[getPrimaryFilter]] to create the name of the filtering field.
     * @see getPrimaryFilter()
     */
    public $name;

    /**
     * @var string md5 of the configuration.
     * Appears only after the combo registration in [[register]]
     * @see register()
     */
    public $configId;

    /**
     * @var array the HTML options for the input element
     */
    public $inputOptions;

    /**
     * @var string the outer element selector, that holds all of related Combos
     */
    public $formElementSelector = 'form';

    /**
     * @var string the language. Default is application language
     */
    public $language;


    /**
     * @var mixed returning arguments
     * Example:
     *
     * ```
     *  ['id', 'password', 'another_column']
     * ```
     *
     * @see getReturn()
     * @see setReturn()
     */
    protected $_return;

    /**
     * @var array renamed arguments
     * Example:
     *
     * ```
     *  [
     *      'new_col_name' => 'old_col_name',
     *      'text' => 'login',
     *      'deep' => 'array.subarray.value' // can extract some value from an array
     *  ]
     * ```
     *
     * @see getName()
     * @see setName()
     */
    protected $_rename;

    /**
     * @var array the static filters
     * Example:
     *
     * ```
     * [
     *      'someStaticValue' => ['format' => 'the_value'],
     *      'type'            => ['format' => 'seller'],
     *      'is_active'       => [
     *          'field'  => 'server',
     *          'format' => new JsExpression('function (id, text, field) {
     *              if (field.isSet()) {
     *                  return 1;
     *              }
     *          }'),
     *      ]
     * ]
     * @see setFilter()
     * @see getFilter()
     */
    protected $_filter;

    /**
     * @var string the name of the primary filter. Default: [[type]]_like.
     * @see getPrimaryFilter
     * @see setPrimaryFilter
     */
    protected $_primaryFilter;

    /**
     * @var boolean|string whether the combo has a primary key
     *   true (default) - the combo has an id in field id
     *            false - the combo does not have an id. The value is equal to the id
     *      some string - the name of the id field
     */
    public $hasId = true;

    /**
     * @var array
     */
    public $_pluginOptions = [];

    /** @inheritdoc */
    public function init()
    {
        parent::init();

        // Set language
        if ($this->language === null && ($language = Yii::$app->language) !== 'en-US') {
            $this->language = substr($language, 0, 2);
        }
        if (!$this->_return) {
            $this->return = ['id'];
        }
        if (!$this->rename) {
            $this->rename = ['text' => $this->type];
        }
    }

    public function run()
    {
        $this->registerClientScript();
        return Html::activeTextInput($this->model, $this->attribute, $this->inputOptions);
    }

    public function registerClientScript()
    {
        $view = \Yii::$app->getView();
        ComboAsset::register($view);

        $pluginOptions  = Json::encode($this->pluginOptions);
        $this->configId = md5($this->type . $pluginOptions);
        $view->registerJs("$.fn.comboConfig().add('{$this->configId}', $pluginOptions);", View::POS_READY, 'combo_' . $this->configId);

        $selector = '#' . Html::getInputId($this->model, $this->attribute);
        $js       = "$('$selector').closest('{$this->formElementSelector}').combo().register('$selector', '$this->configId');";

        $view->registerJs($js);
    }

    public function getReturn()
    {
        return $this->_return;
    }

    /**
     * @return mixed
     */
    public function getRename()
    {
        return $this->_rename;
    }

    /**
     * @return mixed
     */
    public function getFilter()
    {
        return $this->_filter;
    }

    /**
     * @param mixed $filter
     */
    public function setFilter($filter)
    {
        $this->_filter = $filter;
    }

    /**
     * @param mixed $rename
     */
    public function setRename($rename)
    {
        $this->_rename = $rename;
    }

    /**
     * @param mixed $return
     */
    public function setReturn($return)
    {
        $this->_return = $return;
    }

    /**
     * @return string
     * @see _primaryFilter
     */
    public function getPrimaryFilter()
    {
        return $this->_primaryFilter ?: $this->name . '_like';
    }

    /**
     * @param $primaryFilter
     * @see _primaryFilter
     */
    public function setPrimaryFilter($primaryFilter)
    {
        $this->_primaryFilter = $primaryFilter;
    }

    /**
     * Returns the config of the Combo, merges with the passed $config
     *
     * @param array $options
     * @return array
     */
    public function getPluginOptions($options = [])
    {
        return ArrayHelper::merge([
            'name'          => $this->type,
            'type'          => $this->type,
            'hasId'         => $this->hasId,
            'select2Options' => [
                'width'       => '100%',
                'placeholder' => \Yii::t('app', 'Start typing here'),
                'ajax'        => [
                    'url'    => Url::toRoute($this->url),
                    'type'   => 'post',
                    'return' => $this->return,
                    'rename' => $this->rename,
                    'filter' => $this->filter,
                    'data'   => new JsExpression("
                        function (term) {
                            return $(this).data('field').createFilter({
                                '{$this->primaryFilter}': {format: term}
                            });
                        }
                    ")
                ]
            ]
        ], $this->_pluginOptions, $options);
    }

    /**
     * @param array $pluginOptions
     */
    public function setPluginOptions($pluginOptions)
    {
        $this->_pluginOptions = $pluginOptions;
    }
}
