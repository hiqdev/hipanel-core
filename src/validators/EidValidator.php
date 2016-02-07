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

/**
 * Class EidValidator.
 */
class EidValidator extends \yii\validators\RegularExpressionValidator
{
    /**
     * {@inheritdoc}
     */
    public $pattern = '/^[0-9a-z_.:-]+$/i';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->message = \Yii::t('app', '{attribute} does not look like a valid extended id entry');
    }
}
