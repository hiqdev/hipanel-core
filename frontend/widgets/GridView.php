<?php
namespace frontend\widgets;
use frontend\assets\DataTablesAsset;
use frontend\assets\iCheckAsset;
use yii\web\JsExpression;

/**
 * Class GridView
 * Theme GridView widget.
 */
class GridView extends \kartik\grid\GridView
{

    public $dataColumnClass = 'frontend\widgets\DataColumn';

    /**
     * @inheritdoc
     */
    public $tableOptions = [
        'class' => 'table table-condensed table-bordered table-hover dataTable'
    ];
    /**
     * @inheritdoc
     */
    public $options = [
        'class' => 'dataTables_wrapper form-inline',
        'role' => 'grid'
    ];
    /**
     * @inheritdoc
     */
    public $layout = "<div class='table-responsive'>{items}</div>\n<div class='row'><div class='col-xs-6'><div class='dataTables_info'>{summary}</div></div>\n<div class='col-xs-6'><div class='dataTables_paginate paging_bootstrap'>{pager}</div></div></div>";
    /**
     * @inheritdoc
     */
    public function run()
    {
        parent::run();
        $this->registerClientScript();
    }

    private function registerClientScript() {
        $view = $this->getView();
        DataTablesAsset::register($view);
        iCheckAsset::register($view);
        $view->registerJs(<<<'JS'
$(function () {
    var checkAll = $('input.select-on-check-all');
    var checkboxes = $('input.check');

    //$('input').iCheck();
    $('input').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });

    checkAll.on('ifChecked ifUnchecked', function(event) {
        if (event.type == 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });

    checkboxes.on('ifChanged', function(event){
        if(checkboxes.filter(':checked').length == checkboxes.length) {
            checkAll.prop('checked', 'checked');
        } else {
            checkAll.removeProp('checked');
        }
        checkAll.iCheck('update');
    });
});
JS
            , \yii\web\View::POS_READY);
    }
}
