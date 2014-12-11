<?php
use frontend\widgets\GridView;

// use yii\grid\GridView;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Tag');
$this->params['breadcrumbs'][] = $this->title;
?>

<?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<p>
    <?= Html::a(Yii::t('app', 'Create {modelClass}', [
        'modelClass' => 'Tag',
    ]), ['create'], ['class' => 'btn btn-success']) ?>
</p>
<?=Html::a('Scenario 2 Output', 'http://demos.krajee.com/builder-details/tabular-form');?>
<p>
    Need normal ActiveRecord
</p>

<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        ['class' => 'yii\grid\CheckboxColumn'],
        'client',
        [
            'attribute'=>'name',
        ],
        'descr',
        [
            'label'=>'Preview',
            'format'=>'html',
            'value'=>function($data) {
                    return '<span class="label" style="background-color: '.$data['bgcolor'].';color:'.$data['color'].';">'.$data['name'].'</span>';
                },
        ],
        ['class' => 'yii\grid\ActionColumn'],
    ],
]); ?>


<?

//use kartik\widgets\ActiveForm;
//use kartik\builder\TabularForm;
//$form = ActiveForm::begin();
//$attribs = $model->formAttribs;
//unset($attribs['attributes']['color']);
//$attribs['attributes']['status'] = [
//    'type' => TabularForm::INPUT_WIDGET,
//    'widgetClass' => \kartik\widgets\SwitchInput::classname()
//];
//
//echo TabularForm::widget([
//    'dataProvider' => $dataProvider,
//    'form' => $form,
//    'attributes' => $attribs,
//    'gridSettings' => [
//        'floatHeader' => true,
//        'panel' => [
//            'heading' => '<h3 class="panel-title"><i class="glyphicon glyphicon-book"></i> Manage Books</h3>',
//            'type' => GridView::TYPE_PRIMARY,
//            'after'=> Html::a('<i class="glyphicon glyphicon-plus"></i> Add New', '#', ['class'=>'btn btn-success']) . ' ' .
//                Html::a('<i class="glyphicon glyphicon-remove"></i> Delete', '#', ['class'=>'btn btn-danger']) . ' ' .
//                Html::submitButton('<i class="glyphicon glyphicon-floppy-disk"></i> Save', ['class'=>'btn btn-primary'])
//        ]
//    ]
//]);
//$form::end();
?>
