<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\base;

use Yii;

/**
 * Application.
 *
 * Redefined just to set DI definitions through config.
 */
class Application extends \yii\web\Application
{
    public $class;

    public function setDefinitions(array $definitions)
    {
        foreach ($definitions as $class => $definition) {
            Yii::$container->set($class, $definition);
        }
    }
}
