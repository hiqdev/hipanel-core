<?php
use yii\helpers\Html;

?>

<div class="<?= $containerClass; ?>">
    <button class="<?= $buttonClass; ?>" type="button" id="<?= $id; ?>" data-toggle="dropdown" aria-expanded="true">
        <?= Yii::$app->request->get('sort') ? Yii::t('app', 'Sort: ') . $attributes[ltrim(Yii::$app->request->get('sort'), '+-')]['label'] : Yii::t('app', 'Sort'); ?>&nbsp;&nbsp;
        <span class="caret"></span>
    </button>
    <?= Html::ul($links, $options); ?>
</div>
