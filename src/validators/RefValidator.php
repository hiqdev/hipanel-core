<?php

namespace hipanel\validators;

/**
 * Class RefValidator
 * @package hipanel\validators
 */
class RefValidator extends \yii\validators\RegularExpressionValidator
{
    /**
     * @inheritdoc
     */
    public $pattern = '/^[0-9a-z_]+$/i';

    /**
     * @inheritdoc
     */
    public function init () {
        $this->message = \Yii::t('app', '{attribute} does not look like a valid ref entry');
    }
}
