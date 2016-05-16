<?php

$widget = $this->context;

?>
<div class="vertical-view">
<div class="box box-primary">
    <div class="box-body no-padding">
        <div class="mailbox-controls">
            <?= $widget->renderContent('main-actions') ?>
            <?= $widget->renderSearchButton() ?>
            <?= $widget->renderContent('show-actions') ?>
            <div class="box-tools box-bulk-actions pull-right">
                <fieldset disabled="disabled">
                    <?= $widget->renderContent('bulk-actions') ?>
                </fieldset>
            </div>
            <?= $widget->renderSearchForm() ?>
        </div>
        <?= $widget->renderContent('table') ?>
    </div>
</div>
</div>
