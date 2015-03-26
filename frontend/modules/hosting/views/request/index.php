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

$this->title                    = Yii::t('app', 'Request');
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
            'class'                 => MainColumn::className(),
            'attribute'             => 'ip',
        ],
        [
            'attribute'             => 'tags',
            'format'                => 'html',
            'value'                 => function ($model) {
                if (!$model->tags) return "";
                $html = "";
                foreach ($model->tags as $tag) $html .= "<div class='$tag'>$tag</div>";
                return $html;
            },
        ],
        [
            'attribute'             => 'objects_count',
            'format'                => 'html',
            'label'                 => Yii::t('app', 'Links'),
            'value'                 => function ($model) {
                $links = "";
                foreach ($model->objects_count as $class => $stat) {
                    $links .= Html::a("ok", "$class/$class/index");
                }
                return $links;
            }
        ],
    ],
]) ?>
</div>
</div>
