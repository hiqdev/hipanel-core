<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\validators;

use Yii;
use yii\validators\PunycodeAsset;

/**
 * Class DomainValidator is used to validate domain names with a regular expression.
 */
class DomainValidator extends \yii\validators\RegularExpressionValidator
{
    /**
     * {@inheritdoc}
     */
    public $pattern = '/^([a-z0-9][a-z0-9-]*\.)+[a-z0-9][a-z0-9-]*$/';

    /**
     * @var bool
     */
    public $enableIdn = false;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('hipanel', '{attribute} does not look like a valid domain name');
        }
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        if ($this->enableIdn) {
            $model->$attribute = idn_to_ascii($model->$attribute);
        }

        parent::validateAttribute($model, $attribute);
    }

    /**
     * @param string $value the IDN domain name that should be converted to ASCII
     * @return string
     */
    public function convertIdnToAscii($value)
    {
        return idn_to_ascii($value);
    }

    public function convertAsciiToIdn($value)
    {
        return idn_to_utf8($value);
    }


    public function clientValidateAttribute($model, $attribute, $view)
    {
        $js = parent::clientValidateAttribute($model, $attribute, $view);
        if (!$this->enableIdn) {
            return $js;
        }

        PunycodeAsset::register($view);

        return "value = punycode.toASCII(value); $js";
    }

}
