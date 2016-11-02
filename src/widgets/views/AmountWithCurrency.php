<?php

use hipanel\helpers\StringHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

?>
<div class="input-group amount-with-currency-widget">
    <div class="input-group-btn">
        <button type="button" class="btn btn-default iwd-label dropdown-toggle" data-toggle="dropdown">
            <?= StringHelper::getCurrencySymbol(Html::getAttributeValue($model, $selectAttribute)) ?>
        </button>
        <button type=button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span> <span class="sr-only"><?= Yii::t('hipanel', 'Toggle dropdown') ?></span>
        </button>
        <ul class="dropdown-menu">
            <?php foreach (ArrayHelper::remove($selectAttributeOptions, 'items', []) as $k => $v) : ?>
                <li><?= Html::a(StringHelper::getCurrencySymbol($k), '#', ['data-value' => $k, 'data-label' => StringHelper::getCurrencySymbol($k)]) ?>
            <?php endforeach ?>
        </ul>
    </div>
    <?= Html::activeInput($inputAttributeType, $model, $attribute, array_merge(['class' => 'form-control'], $inputOptions)) ?>
    <?= Html::activeHiddenInput($model, $selectAttribute, array_merge(['class' => 'form-control'], $selectAttributeOptions)) ?>
</div>
