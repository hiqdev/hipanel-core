<?php

use frontend\modules\server\widgets\OSFormatter;
use frontend\modules\server\widgets\StateFormatter;
use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\modules\server\widgets\DiscountFormatter;
use frontend\components\widgets\RequestState;
use frontend\components\widgets\Pjax;
use yii\helpers\Json;

/**
 * @var frontend\components\View $this
 */
/**
 * @var frontend\modules\server\models\Server $model
 */

$this->title                   = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Servers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

Pjax::begin(Yii::$app->params['pjax']);
?>
<div class="row" xmlns="http://www.w3.org/1999/html">
    <div class="col-md-5">
        <div class="box box-info">
            <div class="box-body">
                <div class="event-view">
                    <?= DetailView::widget([
                        'model'      => $model,
                        'attributes' => [
                            [
                                'attribute' => 'state',
                                'format'    => 'raw',
                                'value'     => RequestState::widget([
                                    'model'         => $model,
                                    'module'        => 'server',
                                    'clientOptions' => [
                                        'afterChange' => new \yii\web\JsExpression("function () { $.pjax.reload('#content-pjax', {'timeout': 0});}")
                                    ]
                                ])
                            ],
                            'tariff',
                            'tariff_note',
                            [
                                'attribute' => 'ips',
                                'format'    => 'raw',
                                'value'     => \frontend\components\widgets\ArraySpoiler::widget([
                                    'data' => $model->ips
                                ])
                            ],
                            [
                                'attribute' => 'os',
                                'format'    => 'raw',
                                'value'     => OSFormatter::widget([
                                    'osimages'  => $osimages,
                                    'imageName' => $model->osimage
                                ])
                            ],
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
                            [
                                'attribute' => 'expires',
                                'format'    => 'raw',
                                'value'     => StateFormatter::widget([
                                    'model' => $model
                                ])
                            ]
                        ],
                    ]) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="box box-info">
            <div class="box-header"><?= \Yii::t('app', 'VNC to server') ?></div>
            <div class="box-body">
                <?= $this->render('_vnc', ['model' => $model]) ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="box box-info">
            <div class="box-header"><?= \Yii::t('app', 'Power management') ?></div>
            <div class="box-body">
                <?php
                echo Html::beginForm(['reboot'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
                echo Html::hiddenInput('id', $model->id);
                Modal::begin([
                    'toggleButton'  => [
                        'label'    => Yii::t('app', 'Reboot'),
                        'class'    => 'btn btn-default',
                        'disabled' => !$model->isOperable(),
                    ],
                    'header'        => Html::tag('h4', Yii::t('app', 'Confirm server reboot')),
                    'headerOptions' => ['class' => 'label-warning'],
                    'footer'        => Html::button(Yii::t('app', 'Reboot'), [
                        'class'             => 'btn btn-warning',
                        'data-loading-text' => Yii::t('app', 'Rebooting...'),
                        'onClick'           => new \yii\web\JsExpression("$(this).closest('form').submit();")
                    ])
                ]);
                ?>
                <div class="callout callout-warning">
                    <h4><?= Yii::t('app', 'This may cause data loose!') ?></h4>

                    <p>Кароч, тут может все нах похерится. Сечёшь? Точняк уверен?</p>
                </div>
                <?php Modal::end();
                echo Html::endForm(); ?>

                <?php
                echo Html::beginForm(['reset'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
                echo Html::hiddenInput('id', $model->id);
                Modal::begin([
                    'toggleButton'  => [
                        'label'    => Yii::t('app', 'Reset'),
                        'class'    => 'btn btn-default',
                        'disabled' => !$model->isOperable(),
                    ],
                    'header'        => Html::tag('h4', Yii::t('app', 'Confirm server reset')),
                    'headerOptions' => ['class' => 'label-warning'],
                    'footer'        => Html::button(Yii::t('app', 'Reset'), [
                        'class'             => 'btn btn-warning',
                        'data-loading-text' => Yii::t('app', 'Resetting...'),
                        'onClick'           => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading')")
                    ])
                ]);
                ?>
                <div class="callout callout-warning">
                    <h4><?= Yii::t('app', 'This may cause data loose!') ?></h4>

                    <p>Кароч, тут может все нах похерится. Сечёшь? Точняк уверен?</p>
                </div>
                <?php Modal::end();
                echo Html::endForm(); ?>

                <?php
                echo Html::beginForm(['shutdown'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
                echo Html::hiddenInput('id', $model->id);
                Modal::begin([
                    'toggleButton'  => [
                        'label'    => Yii::t('app', 'Shutdown'),
                        'class'    => 'btn btn-default',
                        'disabled' => !$model->isOperable(),
                    ],
                    'header'        => Html::tag('h4', Yii::t('app', 'Confirm server shutdown')),
                    'headerOptions' => ['class' => 'label-warning'],
                    'footer'        => Html::button(Yii::t('app', 'Shutdown'), [
                        'class'             => 'btn btn-warning',
                        'data-loading-text' => Yii::t('app', 'Shutting down...'),
                        'onClick'           => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading')")
                    ])
                ]);
                ?>
                <div class="callout callout-warning">
                    <h4><?= Yii::t('app', 'This may cause data loose!') ?></h4>

                    <p>Кароч, тут может все нах похерится. Сечёшь? Точняк уверен?</p>
                </div>
                <?php Modal::end();
                echo Html::endForm(); ?>

                <?php
                echo Html::beginForm(['power-off'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
                echo Html::hiddenInput('id', $model->id);
                Modal::begin([
                    'toggleButton'  => [
                        'label'    => Yii::t('app', 'Power off'),
                        'class'    => 'btn btn-default',
                        'disabled' => !$model->isOperable(),
                    ],
                    'header'        => Html::tag('h4', Yii::t('app', 'Confirm server power off')),
                    'headerOptions' => ['class' => 'label-warning'],
                    'footer'        => Html::button(Yii::t('app', 'Power OFF'), [
                        'class'             => 'btn btn-warning',
                        'data-loading-text' => Yii::t('app', 'Turning power OFF...'),
                        'onClick'           => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading')")
                    ])
                ]);
                ?>
                <div class="callout callout-warning">
                    <h4><?= Yii::t('app', 'This may cause data loose!') ?></h4>

                    <p>Кароч, тут может все нах похерится. Сечёшь? Точняк уверен?</p>
                </div>
                <?php Modal::end();
                echo Html::endForm(); ?>

                <?php
                echo Html::beginForm(['power-on'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
                echo Html::hiddenInput('id', $model->id);
                Modal::begin([
                    'toggleButton' => [
                        'label'    => Yii::t('app', 'Power on'),
                        'class'    => 'btn btn-default',
                        'disabled' => !$model->isOperable(),
                    ],
                    'header'       => Html::tag('h4', Yii::t('app', 'Confirm server power ON')),
                    'footer'       => Html::button(Yii::t('app', 'Power ON'), [
                        'class'             => 'btn btn-info',
                        'data-loading-text' => Yii::t('app', 'Turning power ON...'),
                        'onClick'           => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading')")
                    ])
                ]);
                ?>
                Включить сервер?
                <?php Modal::end();
                echo Html::endForm(); ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="box box-info">
            <div class="box-header"><?= \Yii::t('app', 'System management') ?></div>
            <div class="box-body">
                <? if ($model->isLiveCDSupported()) {
                    echo Html::beginForm(['boot-live'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
                    echo Html::hiddenInput('id', $model->id);

                    $os_items = [];
                    foreach ($osimageslivecd as $item) {
                        $js         = "$(this).closest('form').find('.livecd-osimage').val({$item['osimage']}).end().submit(); $(this).closest('button').button('loading');";
                        $os_items[] = [
                            'label'   => $item['os'] . ' ' . $item['bitwise'],
                            'url'     => '#',
                            'onclick' => new \yii\web\JsExpression($js)
                        ];

                    }
                    Modal::begin([
                        'toggleButton' => [
                            'label'    => Yii::t('app', 'Live CD'),
                            'class'    => 'btn btn-default',
                            'disabled' => !$model->isOperable(),
                        ],
                        'header'       => Html::tag('h4', Yii::t('app', 'Confirm boot from Live CD')),
                        'footer'       => \yii\bootstrap\ButtonDropdown::widget([
                            'label'    => 'Boot LiveCD',
                            'dropdown' => [
                                'items' => $os_items
                            ],
                            'options'  => [
                                'class'             => 'btn btn-info',
                                'data-loading-text' => Yii::t('app', 'Resetting password...'),
                            ]
                        ])
                    ]);
                    echo Html::hiddenInput('osimage', null, ['class' => 'livecd-osimage']);
                    ?>
                    Это приведет к отключению сервера и загрузке образа Live CD.
                    <?php Modal::end();
                    echo Html::endForm();
                }


                echo Html::beginForm(['power-off'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
                echo Html::hiddenInput('id', $model->id);

                Modal::begin([
                    'toggleButton'  => [
                        'label'    => Yii::t('app', 'Reset password'),
                        'class'    => 'btn btn-default',
                        'disabled' => !$model->isOperable(),
                    ],
                    'header'        => Html::tag('h4', Yii::t('app', 'Confirm server password resetting')),
                    'headerOptions' => ['class' => 'label-warning'],
                    'footer'        => Html::button(Yii::t('app', 'Reset root password'), [
                        'class'             => 'btn btn-warning',
                        'data-loading-text' => Yii::t('app', 'Resetting password...'),
                        'onClick'           => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading')")
                    ])
                ]);
                ?>
                <?= Yii::t('app', 'The password from root account on the server will be re-created and sent on e-mail') ?>
                <?php Modal::end(); ?>
                <?= Html::endForm() ?>

                <?php
                echo Html::beginForm(['reinstall'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
                echo Html::hiddenInput('id', $model->id);
                Modal::begin([
                    'toggleButton'  => [
                        'label'    => Yii::t('app', 'Reinstall OS'),
                        'class'    => 'btn btn-default',
                        'disabled' => !$model->isOperable(),
                    ],
                    'header'        => Html::tag('h4', Yii::t('app', 'Please, select the operating system you want to install')),
                    'headerOptions' => ['class' => 'label-warning'],
                    'footer'        => Html::button(Yii::t('app', 'Reinstall'), [
                        'class'             => 'btn btn-warning',
                        'data-loading-text' => Yii::t('app', 'Reinstalling started...'),
                        'onClick'           => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading');")
                    ])
                ]);
                ?>
                <div class="callout callout-warning">
                    <h4><?= Yii::t('app', 'This will cause full data loss!') ?></h4>
                </div>
                <?= Html::hiddenInput('osimage', null, ['class' => "reinstall-osimage"]) ?>
                <?= Html::hiddenInput('panel', null, ['class' => "reinstall-panel"]) ?>
                <div class="row os-selector">
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div class="panel-heading"><?= \Yii::t('app', 'OS') ?></div>
                            <div class="list-group">
                                <?php
                                foreach ($grouped_osimages['vendors'] as $vendor) { ?>
                                    <div class="list-group-item">
                                        <h4 class="list-group-item-heading"><?= $vendor['name'] ?></h4>

                                        <div class="list-group-item-text os-list">
                                            <? foreach ($vendor['oses'] as $system => $os) {
                                                echo Html::tag('div', Html::radio('os', false, [
                                                    'label' => $os,
                                                    'value' => $system,
                                                    'class' => 'radio'
                                                ]), ['class' => 'radio']);
                                            } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="panel panel-default">
                            <div
                                class="panel-heading"><?= \Yii::t('app', 'Panel and soft') ?></div>
                            <div class="list-group">
                                <?php
                                foreach ($panels as $panel => $panel_name) { ?>
                                    <div class="list-group-item soft-list"
                                         data-panel="<?= $panel ?>">
                                        <h4 class="list-group-item-heading"><?= Yii::t('app', $panel_name) ?></h4>

                                        <div class="list-group-item-text">
                                            <?php foreach ($grouped_osimages['softpacks'][$panel] as $softpack) { ?>
                                                <div class="radio">
                                                    <label>
                                                        <?= Html::radio('panel_soft', false, [
                                                            'data'  => [
                                                                'panel-soft' => 'soft',
                                                                'panel'      => $panel
                                                            ],
                                                            'value' => $softpack['name']
                                                        ]) ?>
                                                        <strong><?= $softpack['name'] ?></strong>
                                                        <small
                                                            style="font-weight: normal"><?= $softpack['description'] ?></small>
                                                        <a class="softinfo-bttn glyphicon glyphicon-info-sign"
                                                           href="#"></a>

                                                        <div class="soft-desc"
                                                             style="display: none;"></div>
                                                    </label>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php Modal::end();
                echo Html::endForm(); ?>
            </div>
        </div>
    </div>
</div>

<?php \frontend\modules\server\assets\OsSelectionAsset::register($this);
$this->registerJs("var osparams = " . Json::encode($grouped_osimages['oses']) . ";
    $('.os-selector').osSelector({
    osparams: osparams
    });
    ", \yii\web\View::POS_READY, 'os-selector-init'); ?>
<?php Pjax::end();