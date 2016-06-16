<?php

//use hipanel\assets\ElementQueryAsset;
//ElementQueryAsset::register($this);
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
.advanced-search[min-width~="150px"] form > div {
    width: 100%;
}

');
$this->registerJs("
$(document).on('pjax:end', function() {
    $('.advanced-search form > div').css({'width': '100%'});
});
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
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
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
