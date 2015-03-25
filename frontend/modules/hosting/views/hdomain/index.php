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

$this->title                    = Yii::t('app', 'Domains');
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
            'class'                 => ClientColumn::className(),
        ],
        [
            'class'                 => MainColumn::className(),
            'attribute'             => 'hdomain',
        ],
        [
            'class'                 => ServerColumn::className(),
        ],
        [
            'attribute'             => 'ip',
        ],
        [
            'attribute'             => 'aliase',
        ],
        [
            'class'                 => RefColumn::className(),
            'attribute'             => 'state',
            'format'                => 'raw',
            'value'                 => function ($model) {
                return State::widget(compact('model'));
            },
            'gtype'                 => 'state,hdomain',
        ],
    ],
]) ?>
</div>
</div>
