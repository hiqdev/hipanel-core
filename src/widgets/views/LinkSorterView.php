<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="<?= $containerClass; ?>" style="display: inline-block;">
    <button class="<?= $buttonClass; ?>" type="button" id="<?= $id; ?>" data-toggle="dropdown" aria-expanded="true">
        <?= $uiModel->sort ? Yii::t('hipanel', 'Sort') . ': ' . $attributes[ltrim($uiModel->sort, '+-')]['label'] : Yii::t('hipanel', 'Sort'); ?>&nbsp;
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <?php foreach ($links as $link) : ?>
            <?= Html::tag('li', $link) ?>
        <?php endforeach; ?>
        <li role="separator" class="divider"></li>
        <?= Html::tag('li', Html::a(Yii::t('hipanel', 'Default sort'), Url::current(['sort' => '']))); ?>
    </ul>
</div>
