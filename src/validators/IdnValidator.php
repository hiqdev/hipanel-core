<?php

namespace hipanel\validators;

use yii\validators\PunycodeAsset;

class IdnValidator extends DomainValidator
{
    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $model->$attribute = idn_to_ascii($model->$attribute);

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
        PunycodeAsset::register($view);
        $js = parent::clientValidateAttribute($model, $attribute, $view);

        return "value = punycode.toASCII(value); $js";
    }
}
