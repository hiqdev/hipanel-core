<?php
use yii\helpers\Html;
use \hipanel\helpers\StringHelper;

$this->registerJs(<<<JS
    $('#$widgetId .dropdown-menu a').on('click', function (event) {
        var elem = $(this);
        $('#$widgetId .iwd-label').text(elem.data('label'));
        $('#$widgetId :hidden').val(elem.data('value'));
    });
JS
);
?>
<div class="form-group">
    <?= Html::activeLabel($model, $inputAttribute) ?>
    <div id="<?= $widgetId ?>" class="input-group">
        <div class="input-group-btn">
            <button type="button" class="btn btn-default iwd-label"><?= StringHelper::getCurrencySymbol(Html::getAttributeValue($model, $selectAttribute)) ?></button>
            <button type=button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span> <span class="sr-only"><?= Yii::t('app', 'Toggle Dropdown') ?></span>
            </button>
            <ul class="dropdown-menu">
                <?php foreach ($selectAttributeOptions as $k => $v) : ?>
                    <li><?= Html::a($v, '#', ['data-value' => $k, 'data-label' => StringHelper::getCurrencySymbol($k)]) ?>
                <?php endforeach; ?>
            </ul>
        </div>
        <?= Html::activeInput($inputAttributeType, $model, $inputAttribute, ['class' => 'form-control']) ?>
        <?= Html::activeHiddenInput($model, $selectAttribute) ?>
    </div>
</div>