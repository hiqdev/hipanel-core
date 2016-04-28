<?php

$widget = $this->context;

?>

<div class="box">
    <div class="box-header with-border">
        <?= $widget->renderContent('main-actions') ?>
        <?= $widget->renderSearchButton() ?>
        <?= $widget->renderContent('show-actions') ?>
        <div class="row">
            <div class="col-md-12">
                <?= $widget->renderSearchForm() ?>
            </div>
        </div>

        <div class="box-bulk-actions box-tools pull-right">
            <fieldset disabled="disabled">
                <?= $widget->renderContent('bulk-actions') ?>
            </fieldset>
        </div>
    </div>
    <div class="box-body no-padding">
        <?= $widget->renderContent('table') ?>
    </div>
</div>
