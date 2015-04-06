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
                </div>
            </div>
        </div>
    </div>
<?php Pjax::end();
