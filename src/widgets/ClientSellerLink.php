<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use hipanel\base\Model;
use Yii;
use yii\helpers\Html;

class ClientSellerLink extends \yii\base\widget
{
    /**
     * @var Model
     */
    public $model;

    public $clientAttribute   = null;
    public $clientIdAttribute = null;
    public $sellerAttribute   = null;
    public $sellerIdAttribute = null;

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
        return isset($this->clientAttribute) ? $this->model->{$this->clientAttribute} : $this->model->getClient();
    }

    public function getClientId()
    {
        return isset($this->clientIdAttribute) ? $this->model->{$this->clientIdAttribute} : $this->model->getClientId();
    }

    public function getSeller()
    {
        if ($this->sellerAttribute === false) {
            return false;
        }

        return isset($this->sellerAttribute) ? $this->model->{$this->sellerAttribute} : $this->model->getSeller();
    }

    public function getSellerId()
    {
        if ($this->sellerAttribute === false || $this->sellerIdAttribute === false) {
            return false;
        }

        return isset($this->sellerIdAttribute) ? $this->model->{$this->sellerIdAttribute} : $this->model->getSellerId();
    }
}
