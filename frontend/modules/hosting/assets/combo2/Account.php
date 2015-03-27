<?php

namespace frontend\modules\hosting\assets\combo2;

use frontend\components\Combo2Config;
use frontend\components\helpers\ArrayHelper;
use yii\web\JsExpression;

class Account extends Combo2Config
{
    /** @inheritdoc */
    public $type = 'account';

    /** @inheritdoc */
    public $url = '/hosting/account/search';

    /** @inheritdoc */
    function getConfig ($config = []) {
        $config = ArrayHelper::merge([
            'clearWhen'     => ['client', 'server'],
            'affects'       => [
                'client' => 'client',
                'server' => 'device'
            ],
            'pluginOptions' => [
                'ajax' => [
                    'return' => ['id', 'client', 'client_id', 'device', 'device_id'],
                    'rename' => ['text' => 'name'],
                    'data'   => new JsExpression("
                        function (term) {
                            return $(this).data('field').form.createFilter({
                                'client': 'client',
                                'server': 'server',
                            });
                        }
                    ")
                ]
            ]
        ], $config);

        return parent::getConfig($config);
    }
}