<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\helpers;

use Yii;
use yii\base\InvalidConfigException;

class SellerHelper
{
    public static function get() : string
    {
        if (Yii::$app->user->isGuest) {
            if (isset(Yii::$app->params['user.seller'])) {
                $seller = Yii::$app->params['user.seller'];
            } else {
                throw new InvalidConfigException('"seller" param must be set');
            }
        } else {
            $seller = Yii::$app->user->identity->seller;
        }

        return $seller;
    }
}
