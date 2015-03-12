<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\widgets\DetailView;
use frontend\components\widgets\RequestState;
use frontend\components\widgets\Pjax;
use yii\helpers\Json;

$this->title                   = Html::encode($model->domain);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Domains'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<? Pjax::begin(Yii::$app->params['pjax']); ?>
<div class="row" xmlns="http://www.w3.org/1999/html">

<div class="col-md-4">
    <div class="box box-info"><div class="box-body">
        <div class="event-view">
            <?= DetailView::widget([
                'model'      => $model,
                'attributes' => [
                    'seller',
                    'client',
                    'domain',
                    [
                        'attribute' => 'state',
                        'format'    => 'raw',
                        'value'     => \frontend\modules\domain\widgets\State::widget(compact('model')),
                    ],
                    [
                        'attribute' => 'nameservers',
                        'format'    => 'raw',
                        'value'     => \frontend\components\widgets\ArraySpoiler::widget([
                            'data' => $model->nameservers
                        ])
                    ],
                    [
                        'attribute' => 'created_date',
                        'format'    => 'date',
                    ],
                    [
                        'attribute' => 'expires',
                        'format'    => 'raw',
                        'value'     => \frontend\modules\domain\widgets\Expires::widget(compact('model')),
                    ]
                ],
            ]) ?>
        </div>
    </div></div>
</div>

    <div class="col-md-4">
        <div class="box box-success">
            <div class="box-header"><?= \Yii::t('app', 'Domain management') ?></div>
            <div class="box-body">
            </div>
        </div>
    </div>

</div>
<?php Pjax::end();
