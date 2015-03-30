<?php

namespace frontend\modules\hosting\assets\combo2;

use frontend\components\Combo2Config;
use frontend\components\helpers\ArrayHelper;
use yii\web\JsExpression;

/**
 * Class Account
 *
 * @package frontend\modules\hosting\assets\combo2
 */
class Account extends Combo2Config
{
    /** @inheritdoc */
    public $type = 'account';

    /** @inheritdoc */
    public $url = '/hosting/account/search';

    public $_return = ['id', 'client', 'client_id', 'device', 'device_id'];

    public $_rename = ['text' => 'name'];

    public $_filter = [
        'client' => 'client',
        'server' => 'server'
    ];

    /** @inheritdoc */
    function getConfig ($config = []) {
        $config = ArrayHelper::merge([
            'clearWhen'     => ['client', 'server'],
            'affects'       => [
                'client' => 'client',
                'server' => 'device'
            ]
        ], $config);

        return parent::getConfig($config);
    }
}