<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use Yii;
use yii\helpers\Html;

class ClientSellerLink extends \yii\base\widget
{
    public $model;

    public function run()
    {
        $res = Html::a($this->model->getClient(), ['@client/view', 'id' => $this->model->getClientId()]);
        if (Yii::$app->user->can('support')) {
            $res .= ' / ' . Html::a($this->model->getSeller(), ['@client/view', 'id' => $this->model->getSellerId()]);
        }
        return $res;
    }
}
