<?php

use hipanel\helpers\StringHelper;
use yii\bootstrap\Html;
use yii\helpers\ArrayHelper;

/**
 * @var string $containerClass
 * @var \yii\base\Model $model
 * @var string $attribute
 * @var string $selectedCurrencyCode
 * @var array $currencyAttributeOptions
 * @var array $currencyDropdownOptions
 */
?>
<div class="input-group">
    <div class="input-group-btn">
        <button
            type="button"
            data-toggle="dropdown"
            class="btn btn-default iwd-label dropdown-toggle <?= $currencyDropdownOptions['disabled'] ? 'disabled' : '' ?>"
            <?= $currencyDropdownOptions['disabled'] ? 'tabindex="-1"' : '' ?>
        >
            <?= StringHelper::getCurrencySymbol($selectedCurrencyCode) ?>
        </button>
        <?php if (!$currencyDropdownOptions['hidden']): ?>
            <button
                type="button"
                data-toggle="dropdown"
                tabindex="-1"
                class="btn btn-default dropdown-toggle <?= $currencyDropdownOptions['disabled'] ? 'disabled' : '' ?>"
            >
                <span class="caret"></span> <span class="sr-only"><?= Yii::t('hipanel', 'Toggle dropdown') ?></span>
            </button>
        <?php endif ?>
        <ul class="dropdown-menu">
            <?php foreach (ArrayHelper::remove($currencyAttributeOptions, 'items', []) as $k => $v) : ?>
                <li>
                    <?= Html::a(StringHelper::getCurrencySymbol($k), 'javascript:void(0);', [
                        'data-value' => $k,
                        'data-label' => StringHelper::getCurrencySymbol($k),
                    ]) ?>
                </li>
            <?php endforeach ?>
        </ul>
    </div>
    <?= Html::activeInput('number', $model, $attribute, $this->context->options) ?>
</div>
