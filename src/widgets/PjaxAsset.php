<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace hipanel\widgets;

/**
 * {@inheritdoc}
 * Uses hiqdev special fork of yiisoft/jquery-pjax.
 */
class PjaxAsset extends \yii\widgets\PjaxAsset
{
    public $sourcePath = '@bower/yii2-pjax';
}
