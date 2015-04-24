<?php

use hipanel\widgets\FileRender as FR;
?>

<div class="attachment">
    <?php print FR::widget(['data' => $attachment, 'object_id' => $object_id, 'object_name' => $object_name, 'answer_id' => $answer_id]); ?>
    <?php /*
    <?php foreach ($attachment as $file) : ?>
        <?php \yii\helpers\VarDumper::dump($file, 10, true);?>

        <img src="http://placehold.it/64x64" alt="..." class="margin">
        <img src="http://placehold.it/64x64" alt="..." class="margin">
        <div class="margin file"><div><i class="fa fa-file-pdf-o fa-2x"></i></div></div>
    <?php endforeach; ?>
    */ ?>
</div>