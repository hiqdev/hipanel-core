<?php

use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var string $buttonClass
 * @var string $containerClass
 * @var string $id
 * @var ?string $label
 * @var array $links
 * @var array $options
 */

?>

<div class="<?= $containerClass ?>" style="display: inline-block;">
    <button class="<?= $buttonClass ?>" type="button" id="<?= $id ?>" data-toggle="dropdown" aria-expanded="true">
        <?= $label ? Html::tag('span', Yii::t('hipanel', 'Sort: {0}', $label), ['class' => $options['label-class']]) : Yii::t('hipanel', 'Sort') ?>
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <?php foreach ($links as $link) : ?>
            <?= Html::tag('li', $link) ?>
        <?php endforeach ?>
        <li role="separator" class="divider"></li>
        <?= Html::tag('li', Html::a(Yii::t('hipanel', 'Default sort'), Url::current(['sort' => '']))) ?>
    </ul>
</div>
