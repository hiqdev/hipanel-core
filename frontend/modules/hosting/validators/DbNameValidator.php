<?php

namespace frontend\modules\hosting\validators;

/**
 * Class LoginValidator is used to validate logins of clients
 */
class DbNameValidator extends \yii\validators\RegularExpressionValidator
{
    /**
     * @inheritdoc
     */
    public $pattern = '/^[a-z0-9_-]{2,31}$/';

    /**
     * @inheritdoc
     */
    public function init () {
        $this->message = \Yii::t('app', '{attribute} should contain only letters, digits, underscores or hyphens and be at least 2 symbols length');
    }
}