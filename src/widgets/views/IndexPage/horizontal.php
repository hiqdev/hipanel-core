<?php

$widget = $this->context;

?>
<div class="horizontal-view">
    <div class="row">
        <div class="col-md-3">
            <div id="scrollspy">
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
            </div>
        </div>
        <div class="col-md-9">
            <div class="box box-primary">
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
        </div>
    </div>
</div>
