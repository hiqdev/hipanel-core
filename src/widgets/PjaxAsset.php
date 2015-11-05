<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace hipanel\widgets;

/**
 * {@inheritdoc}
 * Uses hiqdev special fork of yiisoft/jquery-pjax
 */
class PjaxAsset extends \yii\widgets\PjaxAsset
{
    public $sourcePath = '@hiqdev/jquery-pjax';
}
