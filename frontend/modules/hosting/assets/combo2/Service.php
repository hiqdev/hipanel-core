<?php

namespace frontend\modules\hosting\assets\combo2;

use frontend\components\Combo2Config;
use frontend\components\helpers\ArrayHelper;

/**
 * Class Service
 *
 * @package frontend\modules\hosting\assets\combo2
 */
class Service extends Combo2Config
{
    /** @inheritdoc */
    public $type = 'service';

    /** @inheritdoc */
    public $_primaryFilter = 'name_like';

    /** @inheritdoc */
    public $url = '/hosting/service/search';

    /** @inheritdoc */
    public $_return = ['id', 'client', 'client_id', 'device', 'device_id', 'seller', 'seller_id'];

    /** @inheritdoc */
    public $_rename = ['text' => 'name'];

    /** @inheritdoc */
    public $_filter = [
        'client' => 'client',
        'server' => 'server'
    ];

    public $softType;

    /** @inheritdoc */
    function getConfig ($config = []) {
        $config = ArrayHelper::merge([
            'affects' => [
                'seller' => 'seller',
                'client' => 'client',
                'server' => 'device'
            ],
            'activeWhen' => [
                'server'
            ]
        ], $config);

        return parent::getConfig($config);
    }

    /** @inheritdoc */
    public function getFilter () {
        return ArrayHelper::merge(parent::getFilter(), [
            'soft_type' => ['format' => $this->softType]
        ]);
    }
}