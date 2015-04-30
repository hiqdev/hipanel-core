<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */
?>
<div class="<?= $containerClass; ?>">
    <button class="<?= $buttonClass; ?>" type="button" id="<?= $id; ?>" data-toggle="dropdown" aria-expanded="true">
        <?= Yii::t('app', 'Sort'); ?>&nbsp;&nbsp;
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu" role="menu" aria-labelledby="<?= $id; ?>">
        <?php foreach ($sortNames as $linkName) : ?>
            <li role="presentation"><?= $sort->link($linkName, $linkOptions); ?></li>
        <?php endforeach; ?>
    </ul>
</div>
