<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use hipanel\base\View;
use hipanel\helpers\ArrayHelper;
use yii\base\Object;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

/**
 * Class Combo2Config.
 *
 * @property mixed $return see [[_return]]
 * @property mixed $rename see [[_rename]]
 * @property mixed $filter see [[_filter]]
 * @property mixed $primaryFilter see [[_primaryFilter]]
 */
class Combo2Config extends Object
{
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

    /** @inheritdoc */
    public function init()
    {
        if (!$this->url) {
            $this->url = '/' . implode('/', [$this->type, $this->type, 'search']);
        }
        if (!$this->_return) {
            $this->return = ['id'];
        }
        if (!$this->rename) {
            $this->rename = ['text' => $this->type];
        }
    }

    /**
     * Returns the config of the Combo2, merges with the passed $config
     *
     * @param array $config
     * @return array
     */
    public function getConfig($config = [])
    {
        return ArrayHelper::merge([
            'name'          => $this->type,
            'type'          => $this->type,
            'hasId'         => $this->hasId,
            'pluginOptions' => [
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
        ], $config);
    }

    /**
     * Registers the Combo2 config in the view
     *
     * @param array $config
     * @return bool
     */
    public function register($config = [])
    {
        $view = \Yii::$app->getView();
        Combo2Asset::register($view);

        $config_json    = Json::encode(static::getConfig($config));
        $this->configId = md5($this->type . $config_json);
        $view->registerJs("$.fn.combo2Config().add('{$this->configId}', $config_json);", View::POS_READY, 'combo2Config_' . $this->configId);

        return $this->configId;
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
}
