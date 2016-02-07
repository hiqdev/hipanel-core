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
 * Created by PhpStorm.
 * User: tofid
 * Date: 04.03.15
 * Time: 12:32.
 */
if (\Yii::$app->user->identity->email) {
    echo \cebe\gravatar\Gravatar::widget([
        'email'        => isset($email) ? $email : \Yii::$app->user->identity->email,
        'defaultImage' => 'identicon',
        'options'      => [
            'alt' => isset($alt) ? $alt : \Yii::$app->user->identity->username,
            'class' => !isset($class) ? 'img-circle' : $class,
        ],
        'size'         => !isset($size) ? 25 : $size,
    ]);
}
