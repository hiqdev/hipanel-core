<?php
use yii\bootstrap\ButtonGroup;
use yii\web\View;
use yii\helpers\Url;

$this->title = 'Clients';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-md-1 col-md-offset-11" style="margin: 10px">
        <?=ButtonGroup::widget([
            'buttons'=>[
                ['label'=>Yii::t('app','Tariff'),'options'=>['class'=>($tpl=='_tariff')?'btn-xs active':'btn-xs', 'data-view'=>'_tariff']],
                ['label'=>Yii::t('app','Card'),'options'=>['class'=>($tpl=='_card')?'btn-xs active':'btn-xs', 'data-view'=>'_card']],
            ],
            'options'=>['class'=>'change-view-button']
        ]);?>
    </div>
</div>

<?=$this->render($tpl,['dataProvider'=>$dataProvider]);?>
<?$this->registerJs("
    $( document ).on('click', '.change-view-button button', function() {
        var view = $(this).data('view');

        if ( view == '_tariff' )
            location.replace('".Url::toRoute(['index','tpl'=>'_tariff'])."');
        else
            location.replace('".Url::toRoute(['index','tpl'=>'_card'])."');
    });
", View::POS_END, 'view-options');?>