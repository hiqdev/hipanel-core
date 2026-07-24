<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use hipanel\base\Model;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;

class ClientSellerLink extends \yii\base\Widget
{
    /**
     * @var Model
     */
    public $model;

    public $clientAttribute = 'client';
    public $clientIdAttribute = 'client_id';
    public $sellerAttribute = 'seller';
    public $sellerIdAttribute = 'seller_id';

    public function init()
    {
        if ($this->model === null) {
            throw new InvalidConfigException('Property "model" must be set');
        }
    }

    public function run()
    {
        $user = Yii::$app->user;
        if ($this->getClient() === 'anonym') {
            $result = Html::tag('b', 'anonym');
        } elseif ($this->getClientId() === $user->id || $user->can('access-subclients')) {
            $result = Html::a($this->getClient(), ['@client/view', 'id' => $this->getClientId()]);
        } else {
            $result = $this->getClient();
        }

        if ($user->can('access-subclients') && $this->getSeller() !== false) {
            $result .= ' / ';
            if (
                $user->can('owner-staff') ||
                ($user->can('access-reseller') && $user->identity->hasOwnSeller($this->getSeller()))
            ) {
                $result .= Html::a($this->getSeller(), ['@client/view', 'id' => $this->getSellerId()]);
            } else {
                $result .= $this->getSeller();
            }
        }

        return $result;
    }

    public function getClient()
    {
        return Html::encode($this->getValue($this->clientAttribute));
    }

    public function getClientId()
    {
        return $this->getValue($this->clientIdAttribute);
    }

    public function getSeller()
    {
        return Html::encode($this->getValue($this->sellerAttribute));
    }

    public function getSellerId()
    {
        return $this->getValue($this->sellerIdAttribute);
    }

    public function getValue($attribute)
    {
        return $attribute === false ? false : $this->model->{$attribute};
    }
}
