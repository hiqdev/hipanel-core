<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 10.03.15
 * Time: 13:55
 */

use frontend\components\widgets\Pjax;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;

$h4class = 'text-light-blue';
$this->registerCss(<<<CSS
.skin-form h4 {
    margin: 0 0 5px 0;
    border-bottom: 1px solid #ddd;
    padding-bottom: 15px;
}

.skin-form li {
    float:left;
    width: 50%;
    padding: 5px;
}

.skin-form li > span {
    display: block;
    box-shadow: -1px 1px 2px rgba(0,0,0,0.0);
    cursor: pointer;
}

.skin-form .bg-black-active, .skin-form .bg-black {
    background-color: #fff !important;
}
CSS
);

$this->registerJs(<<<JS
var my_skins = ["skin-blue", "skin-black", "skin-red", "skin-yellow", "skin-purple", "skin-green"];
var my_layout = ["fixed", "layout-boxed"];
function change_layout(cls) {
    if (cls != 'sidebar-collapse') {
        $.each(my_layout, function (i) {
            $("body").removeClass(my_layout[i]);
        });
    }
    if (cls != 'none') {
        $("body").toggleClass(cls);
    }
    //$.AdminLTE.layout.fixSidebar();
}

function change_skin(cls) {
    $.each(my_skins, function (i) {
        $("body").removeClass(my_skins[i]);
    });
    $("body").addClass(cls);
    //store('skin', cls);
    return false;
}
JS
    , \yii\web\View::POS_END);
?>
<?php Pjax::begin(\Yii::$app->params['pjax']); ?>
<?php $form = ActiveForm::begin(['options' => ['class' => 'skin-form', 'data-pjax' => 1]]); ?>
<?= Html::tag('h4', Yii::t('app', 'Layout'), ['class' => $h4class]) ?>
<?= $form->field($model, 'layout')->radioList($model->formLayoutData(), ['item' => function($index, $label, $name, $checked, $value) {
    return '<div class="radio">' . Html::radio($name, $checked, ['label' => $label, 'value' => $value, 'onchange' => new JsExpression('change_layout(this.value);')]) . '</div>';
}]); ?>
<?= Html::tag('h4', Yii::t('app', 'Table height'), ['class' => $h4class]) ?>
<?= $form->field($model, 'table_condensed')->checkbox(); ?>
<?= Html::tag('h4', Yii::t('app', 'Collapsed Sidebar'), ['class' => $h4class]) ?>
<?= $form->field($model, 'collapsed_sidebar')->checkbox(['onchange' => new JsExpression('change_layout("sidebar-collapse");')]); ?>
<?= Html::tag('h4', Yii::t('app', 'Skins'), ['class' => $h4class]) ?>
    <ul class="list-unstyled">
        <?php foreach ($model->skinSampleArray() as $itemData) : ?>
            <?= $this->render('_skin-sample', ['data' => $itemData]); ?>
        <?php endforeach; ?>
    </ul>
    <div class="clearfix"></div>
    <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-default']); ?>
<?php $form::end(); ?>
<?php Pjax::end(); ?>