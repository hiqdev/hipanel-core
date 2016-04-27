<?php

$widget = $this->context;

?>

<div>
    <div class="row">
        <div class="col-md-6">
            <?= $widget->renderContent('main-actions') ?>
            <?= $widget->renderSearchButton() ?>
            <?= $widget->renderContent('show-actions') ?>
        </div>
        <div class="box-bulk-actions col-md-6 text-right">
            <fieldset disabled="disabled">
                <?= $widget->renderContent('bulk-actions') ?>
            </fieldset>
        </div>
    </div>
    <?= $widget->renderSearchForm() ?>
    <?= $widget->renderContent('table') ?>
</div>
