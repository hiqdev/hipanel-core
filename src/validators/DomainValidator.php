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
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->message = Yii::t('app', '{attribute} does not look like a valid domain name');
    }
}
