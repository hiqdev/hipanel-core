<?php $this->registerCss("
.legend-item {
    position: relative;
    display: block;
    padding: 10px 15px 10px 10px;
}
"); ?>

<div class="box box-widget collapsed-box">
    <div class="box-header with-border">
        <h3 class="box-title"><?= Yii::t('hipanel', 'Legend') ?></h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
    </div>
    <div class="box-body no-padding">
        <ul class="nav nav-pills nav-stacked">
            <?php foreach ($items as $item) : ?>
                <li class="legend-item">
                    <b><?= $this->context->getLabel($item) ?></b>
                    <span class="pull-right text-red">
                        <i class="fa fa-square" style="color: <?= $this->context->getColor($item) ?>;"></i>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
