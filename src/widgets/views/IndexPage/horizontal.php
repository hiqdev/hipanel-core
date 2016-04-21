<?php

use hipanel\helpers\Url;
use yii\helpers\Html;

$this->registerJs("
var fixAffixWidth = function() {
  $('[data-spy=\"affix\"]').each(function() {
    $(this).width( $(this).parent().width() );
  });
}
fixAffixWidth();
$(window).resize(fixAffixWidth);
");

$widget = $this->context;

?>

<div class="row">
    <div class="col-md-3">
        <div data-spy="affix_">
            <?= Html::a(Yii::t('app', 'Create'), 'create', ['class' => 'btn btn-success btn-block margin-bottom']) ?>

            <div class="box box-solid collapsed-box">
                <div class="box-header with-border">
                    <h3 class="box-title">Advanced search</h3>
                    <div class="box-tools">
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="box-body ">
                    <?= $widget->renderContent('search-form') ?>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-9">
        <div class="box box-primary">
            <div class="box-body no-padding">
                <div class="mailbox-controls">
                    <?= $widget->renderContent('show-actions') ?>
                    <div class="pull-right">
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
