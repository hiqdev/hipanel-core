


<?php

use yii\helpers\Html;
use hipanel\frontend\assets\ElementQueryAsset;

ElementQueryAsset::register($this);

$this->registerCss('
.affix {
    top: 5px;
}
.affix-bottom {
    position: fixed!important;
}
@media (min-width: 768px) {
    .affix {
        position: fixed;
    }
}
@media (max-width: 768px) {
    .affix {
        position: static;
    }
}
#advancedsearch-part-search[min-width~="300px"] div {
    width: 100%;
}
');
$this->registerJs("
if ($(window).height() > $('#scrollspy').outerHeight(true)) {
    var fixAffixWidth = function() {
        $('#scrollspy').each(function() {
            $(this).width( $(this).parent().width() );
        });
    }
    fixAffixWidth();
    $(window).resize(fixAffixWidth);
    $('#scrollspy').affix({
        offset: {
            top: ($('header.main-header').outerHeight(true) + $('section.content-header').outerHeight(true)) + 15,
            bottom: ($('footer').outerHeight(true)) + 15

        }
    });
    $('a.sidebar-toggle').click(function() {
        setTimeout(function(){
            fixAffixWidth();
        }, 500);
    });
}
");

$widget = $this->context;

?>

<div class="row">
    <div class="col-md-3">
        <div id="scrollspy">
            <?= Html::a(Yii::t('hipanel', 'Create'), 'create', ['class' => 'btn btn-success btn-block margin-bottom']) ?>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><?= Yii::t('hipanel', 'Advanced search') ?></h3>
                    <div class="box-tools">
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div>
                    </div>
                </div>
                <div class="box-body ">
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
