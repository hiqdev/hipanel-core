<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
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
     * @var bool whether to update attribute value with converted IDN.
     * Works only when [[enableIdn]] is set to `true`.
     */
    public $mutateAttribute = true;

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
     * {@inheritdoc}
     */
    public function validateAttribute($model, $attribute)
    {
        $value = mb_strtolower($model->$attribute ?? '');

        if ($this->enableIdn) {
            $value = static::convertIdnToAscii($value);

            if ($this->mutateAttribute) {
                $model->$attribute = $value;
            }
        }

        $result = $this->validateValue($value);

        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
        }
    }

    public static function convertIdnToAscii(?string $value): ?string
    {
        if (empty($value)) {
            return $value;
        }

        return idn_to_ascii($value, IDNA_NONTRANSITIONAL_TO_ASCII, INTL_IDNA_VARIANT_UTS46);
    }

    public static function convertAsciiToIdn(?string $value): ?string
    {
        if (empty($value)) {
            return $value;
        }

        return idn_to_utf8($value, IDNA_NONTRANSITIONAL_TO_ASCII, INTL_IDNA_VARIANT_UTS46);
    }

    public function clientValidateAttribute($model, $attribute, $view)
    {
        $js = parent::clientValidateAttribute($model, $attribute, $view);
        if (!$this->enableIdn) {
            return "value = value.toLowerCase(); $js";
        }

        PunycodeAsset::register($view);

        return "value = punycode.toASCII(value.toLowerCase()); $js";
    }
}
