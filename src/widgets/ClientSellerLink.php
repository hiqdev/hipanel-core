<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
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

    public $clientAttribute   = 'client';
    public $clientIdAttribute = 'client_id';
    public $sellerAttribute   = 'seller';
    public $sellerIdAttribute = 'seller_id';

    public function init()
    {
        if ($this->model === null) {
            throw new InvalidConfigException('Property "model" must be set');
        }
    }

    public function run()
    {
        if ($this->getClient() === 'anonym') {
            $result = Html::tag('b', 'anonym');
        } elseif ($this->getClientId() === Yii::$app->user->id || Yii::$app->user->can('support')) {
            $result = Html::a($this->getClient(), ['@client/view', 'id' => $this->getClientId()]);
        } else {
            $result = $this->getClient();
        }

        if (Yii::$app->user->can('support') && $this->getSeller() !== false) {
            $result .= ' / ' . Html::a($this->getSeller(), ['@client/view', 'id' => $this->getSellerId()]);
        }

        return $result;
    }

    public function getClient()
    {
        return $this->getValue($this->clientAttribute);
    }

    public function getClientId()
    {
        return $this->getValue($this->clientIdAttribute);
    }

    public function getSeller()
    {
        return $this->getValue($this->sellerAttribute);
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
