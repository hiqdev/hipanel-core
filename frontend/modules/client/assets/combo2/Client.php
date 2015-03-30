<?php

namespace frontend\modules\client\assets\combo2;

use frontend\components\Combo2Config;
use frontend\components\helpers\ArrayHelper;

/**
 * Class Client
 *
 * @package frontend\modules\client\assets\combo2
 */
class Client extends Combo2Config
{
    /** @inheritdoc */
    public $type = 'client';

    /** @inheritdoc */
    public $url = '/client/client/search';

    /** @inheritdoc */
    public $_return = ['id'];

    /** @inheritdoc */
    public $_rename = ['text' => 'login'];

    /**
     * @var string the type of client
     */
    public $clientType = 'client';

    /** @inheritdoc */
    public function getFilter () {
        return ArrayHelper::merge(parent::getFilter(), [
            'type' => ['format' => $this->clientType]
        ]);
    }
}