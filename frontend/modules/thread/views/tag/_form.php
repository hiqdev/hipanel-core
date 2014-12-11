<?
use yii\helpers\Html;
use frontend\widgets\HiBox;
use yii\bootstrap\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
$this->registerjs('$("button[data-widget=\'collapse\']").click();', yii\web\View::POS_READY);
?>
<div class="tag-form">
    <?php $form = ActiveForm::begin([]); ?>

    <?php $form::end(); ?>
</div>