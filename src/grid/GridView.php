<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\grid;

use hipanel\helpers\ArrayHelper;
use hiqdev\assets\icheck\iCheckAsset;
use hiqdev\assets\datatables\DataTablesAsset;
use Yii;

/**
 * Class GridView.
 * HiPanel specific GridView.
 */
class GridView extends \hiqdev\higrid\GridView
{
    public $sorter = [
        'class' => '\hipanel\widgets\LinkSorter',
    ];

    /**
     * @inheritdoc
     */
    public $resizableColumns = [
        'resizeFromBody' => false
    ];

    /**
     * @inheritdoc
     */
//    public $tableOptions = [
//        'class' => 'table table-bordered table-hover dataTable'
//    ];

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
    public $layout = "<div class='row'><div class='col-xs-12'>{sorter}</div></div><div class=\"table-responsive\">{items}</div>\n<div class='row'><div class='col-xs-6'><div class='dataTables_info'>{summary}</div></div>\n<div class='col-xs-6'><div class='dataTables_paginate paging_bootstrap'>{pager}</div></div></div>";

    /**
     * @inheritdoc
     */
    static protected function defaultColumns()
    {
        return [
            'seller_id' => ['class' => 'hipanel\modules\client\grid\SellerColumn'],
            'client_id' => ['class' => 'hipanel\modules\client\grid\ClientColumn'],
            'checkbox' => ['class' => 'hipanel\grid\CheckboxColumn'],
            'seller' => [
                'class' => 'hipanel\modules\client\grid\SellerColumn',
                'attribute' => 'seller_id'
            ],
            'client' => [
                'class' => 'hipanel\modules\client\grid\ClientColumn',
                'attribute' => 'client_id'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->tableOptions['class'] .= ' ' . Yii::$app->themeManager->settings->getCssClass('table_condensed');
        parent::run();
        $this->registerClientScript();
    }

    /**
     * {@inheritdoc}
     */
    public static function detailView(array $config = [])
    {
        $config = ArrayHelper::merge(['gridOptions' => ['resizableColumns' => ['resizeFromBody' => true]]], $config);
        return parent::detailView($config);
    }

    /**
     * @inheritdoc
     */
    private function registerClientScript()
    {
        $view = $this->getView();
        DataTablesAsset::register($view);
        iCheckAsset::register($view);
        $view->registerJs(<<<'JS'
$(function () {
    var checkAll = $('input.select-on-check-all');
    var checkboxes = $('input.icheck');

    //$('input').iCheck();
    $('input.icheck, input.select-on-check-all ').iCheck({
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
