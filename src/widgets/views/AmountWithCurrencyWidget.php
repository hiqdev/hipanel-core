<?php

use hipanel\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$currencySymbol = Html::getAttributeValue($model, $selectAttribute);
if ($currencySymbol === null) {
    $selectAttributeOptions = array_merge($selectAttributeOptions, ['value' => 'usd']);
}
$this->registerJs(<<<JS
    $('#$widgetId .dropdown-menu a').on('click', function (event) {
        var elem = $(this);
        $('#$widgetId .iwd-label').text(elem.data('label'));
        $('#$widgetId :hidden').val(elem.data('value'));
    });
    // Add default currency after add new field
    $(".dynamicform_wrapper").on("afterInsert", function(e, item) {
        var currencyInput = $(':input:last', $(item)), currencyValue = '$currencySymbol';
        if (currencyValue === '') currencyInput.val('usd');
    });
JS
);
?>
<div id="<?= $widgetId ?>" class="input-group amount-with-currency-widget">
    <div class="input-group-btn">
        <button type="button" class="btn btn-default iwd-label">
            <?= StringHelper::getCurrencySymbol($currencySymbol !== null ? $currencySymbol : 'usd') ?>
        </button>
        <button type=button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span> <span class="sr-only"><?= Yii::t('app', 'Toggle Dropdown') ?></span>
        </button>
        <ul class="dropdown-menu">
            <?php foreach (ArrayHelper::remove($selectAttributeOptions, 'items', []) as $k => $v) : ?>
            <li><?= Html::a(StringHelper::getCurrencySymbol($k), '#', ['data-value' => $k, 'data-label' => StringHelper::getCurrencySymbol($k)]) ?>
                <?php endforeach ?>
        </ul>
    </div>
    <?= Html::activeInput($inputAttributeType, $model, $attribute, array_merge(['class' => 'form-control'], $inputOptions)) ?>
    <?= Html::activeHiddenInput($model, $selectAttribute, $selectAttributeOptions) ?>
</div>
