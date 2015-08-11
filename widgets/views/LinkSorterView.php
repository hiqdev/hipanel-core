<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="<?= $containerClass; ?>" style="display: inline-block;">
    <button class="<?= $buttonClass; ?>" type="button" id="<?= $id; ?>" data-toggle="dropdown" aria-expanded="true">
        <?= Yii::$app->request->get('sort') ? Yii::t('app', 'Sort') . ': ' . $attributes[ltrim(Yii::$app->request->get('sort'), '+-')]['label'] : Yii::t('app', 'Sort'); ?>&nbsp;&nbsp;
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
        <?php foreach ($links as $link) : ?>
            <?= Html::tag('li', $link) ?>
        <?php endforeach; ?>
        <li role="separator" class="divider"></li>
        <?= Html::tag('li', Html::a(Yii::t('app', 'Default sort'), Url::current(['sort' => null]))); ?>
    </ul>
</div>
