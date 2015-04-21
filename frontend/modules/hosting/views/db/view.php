<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use frontend\components\widgets\Pjax;
use frontend\modules\hosting\grid\DbGridView;


$this->title                   = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Databases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<? Pjax::begin(Yii::$app->params['pjax']); ?>
    <div class="row">
        <div class="col-md-4">
            <?= DbGridView::detailView([
                'model'   => $model,
                'columns' => [
                    'seller_id',
                    'client_id',
                    ['attribute' => 'name'],
                    'service_ip',
                    'description',
                    'password'
                ],
            ]) ?>
        </div>

        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header"><?= \Yii::t('app', 'DB management') ?></div>
                <div class="box-body">
                    <?php
                    echo Html::beginForm(['truncate'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
                    echo Html::hiddenInput('id', $model->id);
                    Modal::begin([
                        'options'       => [
                            'data-backdrop' => 0
                        ],
                        'toggleButton'  => [
                            'label' => Yii::t('app', 'Truncate'),
                            'class' => 'btn btn-warning',
                        ],
                        'header'        => Html::tag('h4', Yii::t('app', 'Confirm DB truncating')),
                        'headerOptions' => ['class' => 'label-warning'],
                        'footer'        => Html::button(Yii::t('app', 'Truncate DB'), [
                            'class'             => 'btn btn-warning',
                            'data-loading-text' => Yii::t('app', 'Truncating DB...'),
                            'onClick'           => new \yii\web\JsExpression("
                                $(this).closest('form').trigger({type: 'submit', 'pjaxOptions': {push: false}});
                                $(this).button('loading');
                            ")
                        ])
                    ]);
                    echo Yii::t('app', 'The database {name} will be fully fully truncated. All tables will be dropped, including data and structure. Are you sure?', ['name' => $model->name]);
                    Modal::end();
                    echo Html::endForm();
                    ?>

                    <?php
                    echo Html::beginForm(['delete'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
                    echo Html::hiddenInput('id', $model->id);
                    Modal::begin([
                        'options'       => [
                            'data-backdrop' => 0
                        ],
                        'toggleButton'  => [
                            'label' => Yii::t('app', 'Delete'),
                            'class' => 'btn btn-danger',
                        ],
                        'header'        => Html::tag('h4', Yii::t('app', 'Confirm DB deleting')),
                        'headerOptions' => ['class' => 'label-danger'],
                        'footer'        => Html::button(Yii::t('app', 'Deleting DB'), [
                            'class'             => 'btn btn-danger',
                            'data-loading-text' => Yii::t('app', 'Deleting DB...'),
                            'onClick'           => new \yii\web\JsExpression("
                                $(this).closest('form').trigger({type: 'submit', 'pjaxOptions': {push: false}});
                                $(this).button('loading');
                            ")
                        ])
                    ]);
                    echo Yii::t('app', 'The database {name} will be deleted. All tables will be dropped, all data will be lost. Are you sure?', ['name' => $model->name]);
                    Modal::end();
                    echo Html::endForm();
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php Pjax::end();
