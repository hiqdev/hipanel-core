<?php
$widget = $this->context;
?>
<div class="box box-widget">
    <div class="box-body no-padding">
        <div class="mailbox-controls">
            <?= $widget->renderContent('show-actions') ?>
            <div class="box-tools box-bulk-actions pull-right">
                <fieldset disabled="disabled">
                    <?= $widget->renderContent('bulk-actions') ?>
                </fieldset>
            </div>
            <div class="box-tools pull-right">
                <?= $widget->renderContent('main-actions') ?>
                &nbsp;
            </div>
        </div>
        <?= $widget->renderContent('table') ?>
    </div>
</div>
