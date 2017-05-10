<?php

use hipanel\helpers\StringHelper;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

?>
<div class="input-group">
    <div class="input-group-btn">
        <button type="button" class="btn btn-default iwd-label dropdown-toggle" data-toggle="dropdown">
            <?= StringHelper::getCurrencySymbol(Html::getAttributeValue($model, $currencyAttributeName)) ?>
        </button>
        <button type=button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <span class="caret"></span> <span class="sr-only"><?= Yii::t('hipanel', 'Toggle dropdown') ?></span>
        </button>
        <ul class="dropdown-menu">
            <?php foreach (ArrayHelper::remove($currencyAttributeOptions, 'items', []) as $k => $v) : ?>
                <li>
                    <?= Html::a(StringHelper::getCurrencySymbol($k), '#', ['data-value' => $k, 'data-label' => StringHelper::getCurrencySymbol($k)]) ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
    <?= Html::activeInput('text', $model, $attribute, $this->context->inputOptions) ?>
</div>
