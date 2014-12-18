<?php

use yii\bootstrap\Modal;
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

    <div class="row">
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading"><?= \Yii::t('app', 'VNC to server') ?></div>
                <div class="panel-body">
                    <?= $this->render('_vnc', ['model' => $model]) ?>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading"><?= \Yii::t('app', 'Power management') ?></div>
                <div class="panel-body">
                    <?php Modal::begin([
                        'toggleButton'  => [
                            'label' => Yii::t('app', 'Reboot'),
                            'class' => 'btn btn-default',
                        ],
                        'header'        => Html::tag('h4', Yii::t('app', 'Confirm server reboot')),
                        'headerOptions' => ['class' => 'label-warning'],
                        'footer'        => Html::a(Yii::t('app', 'Reboot'),
                            ['reboot', 'id' => $model->id],
                            [
                                'class'             => 'btn btn-warning',
                                'data-loading-text' => Yii::t('app', 'Rebooting...'),
                                'onClick'           => new \yii\web\JsExpression("$(this).button('loading')")
                            ])
                    ]);
                    ?>
                    <div class="callout callout-warning">
                        <h4><?= Yii::t('app', 'This may cause data loose!') ?></h4>

                        <p>Кароч, тут может все нах похерится. Сечёшь? Точняк уверен?</p>
                    </div>
                    <?php Modal::end(); ?>


                    <?php Modal::begin([
                        'toggleButton'  => [
                            'label' => Yii::t('app', 'Reset'),
                            'class' => 'btn btn-default',
                        ],
                        'header'        => Html::tag('h4', Yii::t('app', 'Confirm server reset')),
                        'headerOptions' => ['class' => 'label-warning'],
                        'footer'        => Html::a(Yii::t('app', 'Reset'),
                            ['reset', 'id' => $model->id],
                            [
                                'class'             => 'btn btn-warning',
                                'data-loading-text' => Yii::t('app', 'Reseting...'),
                                'onClick'           => new \yii\web\JsExpression("$(this).button('loading')")
                            ])
                    ]);
                    ?>
                    <div class="callout callout-warning">
                        <h4><?= Yii::t('app', 'This may cause data loose!') ?></h4>

                        <p>Кароч, тут может все нах похерится. Сечёшь? Точняк уверен?</p>
                    </div>
                    <?php Modal::end(); ?>
                </div>
            </div>
        </div>
    </div>

</div>
