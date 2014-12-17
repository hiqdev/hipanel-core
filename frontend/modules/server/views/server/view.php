<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\modules\server\widgets\DiscountFormatter;
use app\modules\object\widgets\RequestState;

$this->title                   = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="event-view">
    <?= DetailView::widget([
        'model'      => $model,
        'attributes' => [
            [
                'attribute' => 'state',
                'format'    => 'html',
                'value'     => RequestState::widget([
                    'model' => $model
                ])
            ],
            'tariff',
            'tariff_note',
            'ips',
            'os',
            [
                'attribute' => 'panel',
                'format'    => 'raw',
//                'value'     => switch ($model->panel) {
//                        case 'isp':
//                            return Html::a('isp', "http://{$model->ip}:1500/", ['target' => '_blank']);
//                            break;
//                        default:
//                            return $model->panel;
//                            break;
//                    }
//                }
            ],
            [
                'attribute' => 'sale_time',
                'format'    => 'date'
            ],
            [
                'attribute' => 'discounts',
                'format'    => 'raw',
                'value'     => DiscountFormatter::widget([
                    'current' => $model->discounts['fee']['current'],
                    'next'    => $model->discounts['fee']['next'],
                ])
            ],
        ],
    ]) ?>

    <? if ($model->vnc['leftTime']) { ?>
        <div class="panel panel-primary">
            <div class="panel-heading"><?= \Yii::t('app', 'VNC to server') ?></div>
            <div class="panel-body">
                <?= Html::tag('span', \Yii::t('app', 'Enabled'), ['class' => 'label label-success']) ?>
                <?= \Yii::t('app', 'Time left') ?>: <?= \Yii::$app->formatter->asRelativeTime($model->vnc['leftTime']) ?>
            </div>
        </div>
    <? } ?>
</div>
