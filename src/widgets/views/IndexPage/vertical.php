<?php

$widget = $this->context;

?>

<div>
    <div class="row">
        <div class="box-actions col-md-6">
            <?= $widget->renderContent('main-actions') ?>
            <?= $widget->renderSearchButton() ?>
            <?= $widget->renderContent('show-actions') ?>
        </div>
    </div>
    <div class="box-bulk-actions col-md-6 text-right">
        <fieldset disabled="disabled">
            <?= $widget->renderContent('show-actions') ?>
        </fieldset>
    </div>
    <?= $widget->renderContent('search-form') ?>
    <?= $widget->renderContent('table') ?>
</div>
