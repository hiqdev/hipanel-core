<?php

use yii\helpers\Html;
use yii\web\View;

/**
 * @var View $this
 * @var string $id
 * @var string $hiddenInput
 **/

?>

<div id="<?= $id ?>" class="file-input">
    <article>
        <div class="overlay">
            <i>
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                  <path d="M19.479 10.092c-.212-3.951-3.473-7.092-7.479-7.092-4.005 0-7.267 3.141-7.479 7.092-2.57.463-4.521 2.706-4.521 5.408 0 3.037 2.463 5.5 5.5 5.5h13c3.037 0 5.5-2.463 5.5-5.5 0-2.702-1.951-4.945-4.521-5.408zm-7.479-1.092l4 4h-3v4h-2v-4h-3l4-4z"/>
                </svg>
            </i>
            <p>
                <?= Yii::t('hipanel', 'Drop files to upload') ?>
            </p>
        </div>

        <section>
            <?= $hiddenInput ?>
            <header>
                <?= Html::tag('p', Yii::t('hipanel', 'Drag and drop your files anywhere or')) ?>
                <?= Html::button(Yii::t('hipanel', 'Upload a file'), ['class' => 'btn btn-default', 'type' => 'button']) ?>
            </header>

            <ul class="gallery">
                <li class="empty">
                    <i class="glyphicon glyphicon-folder-open"></i>
                    <span><?= Yii::t('hipanel', 'No files selected') ?></span>
                </li>
            </ul>
        </section>
    </article>


    <template class="template">
        <li>
            <article>
                <img src="" alt="upload preview">
                <section>
                    <h1></h1>
                    <div>
                        <p class="size"></p>
                        <button class="delete">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <path d="M3 6l3 18h12l3-18h-18zm19-4v2h-20v-2h5.711c.9 0 1.631-1.099 1.631-2h5.316c0 .901.73 2 1.631 2h5.711z"/>
                            </svg>
                        </button>
                    </div>
                </section>
            </article>
        </li>
    </template>

</div>
