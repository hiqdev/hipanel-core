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

use hipanel\module\client\models\Client;
use Yii;
use yii\base\InvalidConfigException;

class UserHelper
{
    public static function getLogin(): ?string
    {
        return Yii::$app->user->isGuest
            ? null
            : Yii::$app->user->login;
    }

    public static function getId(): ?integer
    {
        return Yii::$app->user->isGuest
            ? null
            : (integer) Yii::$app->user->id;
    }

    public static function getSeller(): string
    {
        if (!Yii::$app->user->isGuest) {
            return Yii::$app->user->identity->seller;
        }

        return self::getDefaultSeller();
    }

    public static function getSellerId(): integer
    {
        if (!Yii::$app->user->isGuest) {
            return Yii::$app->user->seller_id;
        }

        $seller = self::getDefaultSeller();
        $sellerModel = Yii::$app->get('cache')->getOrSet([__CLASS__, __METHOD__, $seller], function () use ($seller) {
            return Client::perform('check', [
                'client' => $seller,
            ]);
        });

        return $sellerModel->id;
    }

    /**
     * @throw InvalidConfigException
     */
    protected static function getDefaultSeller(): string
    {
        if (empty(Yii::$app->params['user.seller'])) {
            throw new InvalidConfigException('"seller" param must be set');
        }

        return (string) Yii::$app->params['user.seller'];
    }
}
