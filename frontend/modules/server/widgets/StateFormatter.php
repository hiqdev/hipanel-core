<?php
namespace frontend\modules\server\widgets;

use yii\base\InvalidParamException;
use yii\base\Widget;
use yii\helpers\Html;

class StateFormatter extends Widget
{
    /**
     * @var \frontend\modules\server\models\Server
     */
    public $model;

    public function init () {
        parent::init();
        if (!($this->model instanceof \frontend\modules\server\models\Server)) {
            throw new InvalidParamException("Model should be an instance of Server model");
        }
    }

    public function run () {
        if ($this->model->state != 'blocked') {
            $value = \yii::$app->formatter->asDate($this->model->expires);
        } else {
            $value = \yii::t('app', 'Blocked') . ' ' . \frontend\components\Re::l($this->model->block_reason_label);
        }

        $class = ['label'];

        if (strtotime("+7 days", time()) < strtotime($this->model->expires)) {
            $class[] = 'label-info';
        } elseif (strtotime("+3 days", time()) < strtotime($this->model->expires)) {
            $class[] = 'label-warning';
        } else {
            $class[] = 'label-danger';
        }
        $html = Html::tag('span', $value, ['class' => implode(' ', $class)]);

        return $html;
    }
}
