<?php

use frontend\components\grid\CheckboxColumn;
use frontend\modules\client\grid\ClientColumn;
use frontend\components\grid\EditableColumn;
use frontend\modules\client\grid\ResellerColumn;
use frontend\components\grid\SwitchColumn;
use frontend\components\grid\GridView;
use frontend\modules\domain\widgets\Expires;
use frontend\modules\domain\widgets\State;
use frontend\models\Ref;
use yii\helpers\Html;

$this->title                    = Yii::t('app', 'Contact');
$this->params['breadcrumbs'][]  = $this->title;
$this->params['subtitle']       = Yii::$app->request->queryParams ? 'filtered list' : 'full list';

?>

<div class="box box-primary">
    <div class="box-body">
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        [
            'class'                 => CheckboxColumn::className(),
        ],
        [
            'class'                 => ResellerColumn::className(),
        ],
        [
            'class'                 => ClientColumn::className(),
        ],
        [
            'attribute'             => 'name',
            'label'                 => Yii::t('app', 'Name'),
            'format'                => 'html',
            'value'                 => function ($model) {
                return Html::a($model->name, ['view', 'id' => $model->id], ['class' => 'bold']);
            },
        ],
        [
            'attribute'             => 'email',
            'format'                => 'html',
            'value'                 => function ($model) {
                return Html::a($model->email, ['view', 'id' => $model->id], ['class' => 'bold']);
            },
        ],
        [
            'class'         => 'yii\grid\ActionColumn',
            'template'      => '{view} {update} {delete} {copy}',
            'buttons'       => [
                'copy'          => function ($url, $model, $key) {
                    return Html::a('<span class="glyphicon glyphicon-copy"></span>', ['view','id'=>$model['id']]);
                },
            ],
        ],
    ],
]) ?>
    </div>
</div>
