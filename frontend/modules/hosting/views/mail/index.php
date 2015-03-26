<?php

use frontend\components\grid\GridView;
use frontend\components\grid\CheckboxColumn;
use frontend\components\grid\ClientColumn;
use frontend\components\grid\EditableColumn;
use frontend\components\grid\ResellerColumn;
use frontend\components\grid\ServerColumn;
use frontend\components\grid\SwitchColumn;
use frontend\components\grid\MainColumn;
use frontend\components\grid\RefColumn;
use frontend\modules\domain\widgets\Expires;
use frontend\modules\domain\widgets\State;
use frontend\models\Ref;
use yii\helpers\Html;

$this->title                    = Yii::t('app', 'Mail');
$this->params['breadcrumbs'][]  = $this->title;
$this->params['subtitle']       = Yii::$app->request->queryParams ? 'filtered list' : 'full list';

?>

<div class="box">
    <div class="box-header">
        <?= Html::a(Yii::t('app', 'Create {modelClass}', [
            'modelClass' => 'mail',
        ]), ['create'], ['class' => 'btn btn-success']); ?>
    </div>
    <div class="box-body">
<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel'  => $searchModel,
    'columns'      => [
        [
            'class'                 => CheckboxColumn::className(),
        ],
        [
            'class'                 => ClientColumn::className(),
        ],
        [
            'class'                 => ServerColumn::className(),
        ],
        [
            'class'                 => AccountColumn::className(),
        ],
        [
            'attribute'             => 'mail',
        ],
        [
            'attribute'             => 'state',
        ],
        [
            'class'                 => 'yii\grid\ActionColumn',
            'template'              => '{block} {unblock} {update} {enable} {disable} {delete}',
        ],

   ],
]) ?>
    </div>
</div>
