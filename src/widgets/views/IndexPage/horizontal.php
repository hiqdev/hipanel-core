<?php

$widget = $this->context;

?>
<div class="horizontal-view">
    <div class="row horizontal-content clearfix">
        <div class="col-md-2">
            <div class="content-sidebar">
                <div class="content-sidebar__inner clearfix">
                    <?= $widget->renderContent('main-actions') ?>
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?= Yii::t('hipanel', 'Advanced search') ?></h3>
                            <div class="box-tools">
                                <div class="box-tools pull-right">
                                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="box-body advanced-search">
                            <?= $widget->renderSearchForm(['options' => ['displayNone' => false]]) ?>
                        </div>
                    </div>
                    <?= $widget->renderContent('legend') ?>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="box box-widget">
                <div class="box-body no-padding">
                    <div class="mailbox-controls">

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
                    </div>
                    <?= $widget->renderContent('table') ?>
                </div>
            </div>
        </div>
    </div>
</div>
