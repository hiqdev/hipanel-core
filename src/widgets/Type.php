<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use hipanel\base\Model;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;

class Type extends \hipanel\widgets\Label
{
    /** @var Model */
    public $model;

    /** @var[] which contains:
     * key - css class name which will be used to highlight label
     * values - states or types, that represent current CSS class
     * Examples
     * ~~~
     * ['info' => ['ok', 'expired']]
     * ~~~
     **/
    public $values = [];

    /** @var array inherits $values */
    public $defaultValues = [];

    /**
     * @var string Dictionary name for i18n module to translate refs
     */
    public $i18nDictionary = 'hipanel';

    /** @var string field */
    public $field = 'state';

    public function init()
    {
        $possible = [];
        $field = $this->model->{$this->field};

        foreach ($this->defaultValues as $key => $values) {
            $possible[$key] = ArrayHelper::merge($values, $this->values[$key] ?: []);
        }

        $this->values = ArrayHelper::merge($possible, $this->values);

        foreach ($this->values as $classes => $values) {
            if (in_array($field, $values, true)) {
                $class = $classes;
                break;
            }
        }

        $this->color = isset($class) ? $class : 'warning';

        if ($this->model->hasAttribute("{$this->field}_label") && $this->model->getAttribute("{$this->field}_label") !== null) {
            $label = $this->model->getAttribute("{$this->field}_label");
        } else {
            $label = Inflector::titleize($this->model->{$this->field});
        }
        $this->label  = Yii::t($this->i18nDictionary, $label);
    }
}
