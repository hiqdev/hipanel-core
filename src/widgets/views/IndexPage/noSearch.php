<?php
$widget = $this->context;
?>
<div class="box box-default">
    <div class="box-body no-padding">
        <div class="mailbox-controls">
            <?= $widget->renderContent('show-actions') ?>
            <div class="box-tools box-bulk-actions pull-right">
                <fieldset disabled="disabled">
                    <?= $widget->renderContent('bulk-actions') ?>
                </fieldset>
            </div>
        </div>
        <?= $widget->renderContent('table') ?>
    </div>
</div>
