<?php

use hipanel\helpers\Url;
use hipanel\widgets\ModalButton;
use yii\helpers\Html;

$modalButton = ModalButton::begin([
    'model'     => $model,
    'scenario'  => $scenario,
    'button'    => [
        'label'                 => $button,
    ],
    'form'      => [
        'enableAjaxValidation'  => true,
        'validationUrl'         => $validationUrl,
    ],
    'modal'     => [
        'header'                => Html::tag($headerTag, $header),
        'headerOptions'         => $headerOptions,
        'footer'                => $footer,
    ],
]);

if ($warning) {
?>
    <div class="callout callout-warning">
        <h4><?= $warning ?></h4>
    </div>
<?php
}

if ($blockReasons) {
    echo $modalButton->form->field($model, 'type')->dropDownList($blockReasons);
}
echo $modalButton->form->field($model, 'comment');
$modalButton->end();

