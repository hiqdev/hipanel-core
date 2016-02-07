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

use Yii;
use yii\helpers\Html;

class ClientSellerLink extends \yii\base\widget
{
    public $model;

    public function run()
    {
        $res = $this->model->getClient() === 'anonym'
            ? Html::tag('b', 'anonym')
            : Html::a($this->model->getClient(), ['@client/view', 'id' => $this->model->getClientId()]);
        if (Yii::$app->user->can('support')) {
            $res .= ' / ' . Html::a($this->model->getSeller(), ['@client/view', 'id' => $this->model->getSellerId()]);
        }
        return $res;
    }
}
