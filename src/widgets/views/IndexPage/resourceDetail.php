<div class="box box-widget">
    <div class="box-header-wrapper">
        <div class="box-header">
            <h4 class="box-title">
                <?= $this->context->renderContent('title') ?>
            </h4>
            <div class="box-tools pull-right">
                <?= $this->context->renderContent('actions') ?>
            </div>
        </div>
    </div>
    <div class="box-body no-padding">
        <div class="overlay" style="display: none;">
            <i class="fa fa-refresh fa-spin"></i>
        </div>
        <?= $this->context->renderContent('table') ?>
    </div>
</div>
