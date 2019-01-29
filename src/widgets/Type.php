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
     * values - states or types, that represent current CSS class. Wildcards can be used.
     * Examples
     * ~~~
     * [
     *     'info' => ['ok', 'expired', 'error:recoverable'],
     *     'warning' => ['problem', 'error:*'],
     * ]
     * ~~~
     *
     * In the `warning` section, `error:*` wildcard is used. In this case the value
     * will be checked for exact matches, then for wildcard matches.
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
    /** @var string $labelField */
    public $labelField;

    public function init()
    {
        $possible = [];
        foreach ($this->defaultValues as $key => $values) {
            $possible[$key] = ArrayHelper::merge($values, $this->values[$key] ?? []);
        }
        $this->values = ArrayHelper::merge($possible, $this->values);

        $this->setColor($this->pickColor());
        $this->setLabel(Yii::t($this->i18nDictionary, $this->getModelLabel()));
    }

    protected function pickColor(): string
    {
        $field = $this->getFieldValue();

        foreach ($this->values as $classes => $values) {
            if (\in_array($field, $values, true)) {
                return $classes;
            }
        }

        foreach ($this->values as $classes => $values) {
            foreach ($values as $value) {
                if (fnmatch($value, $field)) {
                    return $classes;
                }
            }
        }

        return 'warning'; // fallback
    }

    protected function getModelLabel(): string
    {
        $labelField = $this->getLabelField();

        if ($this->model->hasAttribute($labelField) && $this->model->getAttribute($labelField) !== null) {
            return $this->model->getAttribute($labelField);
        }

        return $this->titlelize($this->getFieldValue());
    }

    protected function getFieldValue(): string
    {
        return $this->model->{$this->field};
    }

    protected function getLabelField(): string
    {
        return $this->labelField ?? ($this->field . '_label');
    }

    /**
     * @param string $label
     * @return string
     */
    protected function titlelize($label): string
    {
        return Inflector::titleize($label);
    }
}
