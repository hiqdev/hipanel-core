<?php
/** @var \hipanel\widgets\IndexPage $widget */
$widget = $this->context;

?>
<div class="vertical-view">
    <div class="box box-widget">
        <div class="box-body no-padding">
            <?= $widget->renderExportProgress() ?>
            <div class="mailbox-controls">
                <?= $widget->renderContent('main-actions') ?>
                <?= $widget->renderSearchButton() ?>

                <?= $widget->renderLayoutSwitcher() ?>
                <?= $widget->renderExport() ?>
                <?= $widget->renderContent('sorter-actions') ?>
                <?= $widget->renderContent('representation-actions') ?>
                <?= $widget->renderPerPage() ?>
                <?= $widget->renderContent('show-actions') ?>

                <div class="box-tools box-bulk-actions pull-right">
                    <?= $widget->renderContent('alt-actions') ?>
                    <fieldset disabled="disabled">
                        <?= $widget->renderContent('bulk-actions') ?>
                    </fieldset>
                </div>
                <?= $widget->renderSearchForm() ?>
            </div>
            <?= $widget->renderContent('table') ?>
        </div>
    </div>
    <?= $widget->renderContent('legend') ?>
</div>
