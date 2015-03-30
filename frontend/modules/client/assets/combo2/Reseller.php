<?php

namespace frontend\modules\client\assets\combo2;

use frontend\components\Combo2Config;
use frontend\components\helpers\ArrayHelper;
use yii\web\JsExpression;

/**
 * Class Reseller
 *
 * @package frontend\modules\client\assets\combo2
 */
class Reseller extends Client
{
    /** @inheritdoc */
    public $type = 'reseller';

    /** @inheritdoc */
    public $clientType = 'reseller';

    /** @inheritdoc */
    public $primaryFilter = 'client_like';
}