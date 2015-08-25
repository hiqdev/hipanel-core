<?php

namespace hipanel\validators;

/**
 * Class EidValidator
 * @package hipanel\validators
 */
class EidValidator extends \yii\validators\RegularExpressionValidator
{
    /**
     * @inheritdoc
     */
    public $pattern = '/^[0-9a-z_.:-]+$/i';

    /**
     * @inheritdoc
     */
    public function init () {
        $this->message = \Yii::t('app', '{attribute} does not look like a valid extended id entry');
    }
}
