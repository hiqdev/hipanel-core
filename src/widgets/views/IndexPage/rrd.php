<?php

$widget = $this->context;

?>
<div class="vertical-view">
    <div class="box box-primary">
        <div class="box-body no-padding">
            <div class="mailbox-controls">
                <?= $widget->renderSearchForm() ?>
            </div>
            <?= $widget->renderContent('table') ?>
        </div>
    </div>
</div>