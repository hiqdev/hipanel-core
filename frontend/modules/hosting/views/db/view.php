<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\components\widgets\RequestState;
use frontend\components\widgets\Pjax;
use yii\helpers\Json;

$this->title                   = Html::encode($model->name);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Databases'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<? Pjax::begin(Yii::$app->params['pjax']); ?>
    <div class="row" xmlns="http://www.w3.org/1999/html">
        <div class="col-md-4">
            <div class="box box-info">
                <div class="box-body">
                    <div class="event-view">
                        <?= DetailView::widget([
                            'model'      => $model,
                            'attributes' => [
                                'seller',
                                'client',
                                'name',
                                'service_ip',
                            ],
                        ]) ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="box box-success">
                <div class="box-header"><?= \Yii::t('app', 'DB management') ?></div>
                <div class="box-body">
                    <?php
                    echo Html::beginForm(['truncate'], "POST", ['data' => ['pjax' => 1], 'class' => 'inline']);
                    echo Html::hiddenInput('id', $model->id);
                    Modal::begin([
                        'toggleButton'  => [
                            'label'    => Yii::t('app', 'Truncate'),
                            'class'    => 'btn btn-danger',
                        ],
                        'header'        => Html::tag('h4', Yii::t('app', 'Confirm DB truncating')),
                        'headerOptions' => ['class' => 'label-warning'],
                        'footer'        => Html::button(Yii::t('app', 'Truncate DB'), [
                            'class'             => 'btn btn-warning',
                            'data-loading-text' => Yii::t('app', 'Truncating DB...'),
                            'onClick'           => new \yii\web\JsExpression("$(this).closest('form').submit(); $(this).button('loading')")
                        ])
                    ]);
                    echo Yii::t('app', 'The database {name} will be fully fully truncated. All tables will be dropped, including data and structure. Are you sure?', ['name' => $model->name]);
                    Modal::end();
                    Html::endForm();
                    ?>
                </div>
            </div>
        </div>
    </div>
<?php Pjax::end();
