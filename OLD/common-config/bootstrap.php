<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

Yii::setAlias('hipanel',  dirname(dirname(__DIR__)));
Yii::setAlias('project',  dirname(dirname(dirname(dirname(dirname(dirname(__DIR__)))))));
Yii::setAlias('common',   '@hipanel/common');
Yii::setAlias('frontend', '@hipanel/frontend');
Yii::setAlias('backend',  '@hipanel/backend');
Yii::setAlias('console',  '@hipanel/console');
