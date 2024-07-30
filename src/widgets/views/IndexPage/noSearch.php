<?php $widget = $this->context ?>
<div class="box box-widget">
    <div class="box-header with-border no-padding">
        <div class="mailbox-controls">
            <?= $widget->renderContent('sorter-actions') ?>
            <?= $widget->renderContent('representation-actions') ?>
            <?= $widget->renderPerPage() ?>
            <?= $widget->renderContent('show-actions') ?>
        </div>
    </div>
    <div class="box-body no-padding">
        <div class="mailbox-controls">
            <div class="box-tools box-bulk-actions pull-right">
                <fieldset disabled="disabled">
                    <?= $widget->renderContent('bulk-actions') ?>
                </fieldset>
            </div>
            <div class="box-tools pull-right">
                <?= $widget->renderContent('main-actions') ?>
            </div>
        </div>
        <?= $widget->renderContent('table') ?>
    </div>
</div>
