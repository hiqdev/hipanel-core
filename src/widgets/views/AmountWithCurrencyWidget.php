<?php

use hipanel\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$currencySymbol = Html::getAttributeValue($model, $selectAttribute);
$this->registerJs(<<<JS
    $('#$widgetId .dropdown-menu a').on('click', function (event) {
        var elem = $(this);
        $('#$widgetId .iwd-label').text(elem.data('label'));
        $('#$widgetId :hidden').val(elem.data('value'));
    });
JS
);
?>
<div id="<?= $widgetId ?>" class="input-group amount-with-currency-widget">
    <div class="input-group-btn">
        <button type="button" class="btn btn-default iwd-label">
            <?= StringHelper::getCurrencySymbol($currencySymbol) ?>
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
