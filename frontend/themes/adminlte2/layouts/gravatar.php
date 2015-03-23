<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 04.03.15
 * Time: 12:32
 */
if (\Yii::$app->user->identity->email) {
    print \cebe\gravatar\Gravatar::widget([
        'email'        => \Yii::$app->user->identity->email,
        'defaultImage' => 'identicon',
        'options'      => [
            'alt' => \Yii::$app->user->identity->username,
            'class' => !isset($class) ? 'img-circle' : $class,
        ],
        'size'         => !isset($size) ? 25 : $size,
    ]);
}
