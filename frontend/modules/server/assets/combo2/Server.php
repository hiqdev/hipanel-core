<?php

namespace frontend\modules\server\assets\combo2;

use frontend\components\Combo2Config;
use frontend\components\helpers\ArrayHelper;
use yii\web\JsExpression;

class Server extends Combo2Config
{
    /** @inheritdoc */
    public $type = 'server';

    /** @inheritdoc */
    public $url = '/server/server/search';

    /** @inheritdoc */
    function getConfig ($config = []) {
        $config = ArrayHelper::merge([
            'clearWhen'     => ['client'],
            'affects'       => [
                'client' => 'client'
            ],
            'pluginOptions' => [
                'ajax' => [
                    'data' => new JsExpression("
                        function (term) {
                            return $(this).data('field').form.createFilter({
                                'client': 'client',
                                'server_like': {format: term},
                                'return': ['id', 'client', 'client_id'],
					            'rename': {'text': 'name'}
                            });
                        }
                    ")
                ]
            ]
        ], $config);

        return parent::getConfig($config);
    }
}