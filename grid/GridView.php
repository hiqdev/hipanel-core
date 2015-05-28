<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\grid;

use hiqdev\assets\icheck\iCheckAsset;
use hiqdev\assets\datatables\DataTablesAsset;
use hipanel\modules\client\grid\ClientColumn;
use hipanel\modules\client\grid\ResellerColumn;
use Yii;

/**
 * Class GridView
 * Theme GridView widget.
 */
class GridView extends \hiqdev\higrid\GridView
{
    public $dataColumnClass = 'hipanel\grid\DataColumn';

    static public $detailViewClass = 'hipanel\grid\DetailView';

    /** @inheritdoc */
    public $tableOptions = [
        'class' => 'table table-bordered table-hover dataTable'
    ];

    /** @inheritdoc */
    public $options = [
        'class' => 'dataTables_wrapper form-inline',
        'role'  => 'grid'
    ];

    /** @inheritdoc */
    public $layout = "{items}\n<div class='row'><div class='col-xs-6'><div class='dataTables_info'>{summary}</div></div>\n<div class='col-xs-6'><div class='dataTables_paginate paging_bootstrap'>{pager}</div></div></div>";

    static public function detailView (array $config = []) {
        $class = static::$detailViewClass ?: DetailView::className();

        return call_user_func([$class, 'widget'], array_merge(['gridViewClass' => get_called_class()], $config));
    }

    static protected $_defaultColumns;

    static protected function defaultColumns () {
        return [
            'checkbox'  => ['class' => CheckboxColumn::className()],
            'seller_id' => ['class' => ResellerColumn::className()],
            'client_id' => ['class' => ClientColumn::className()],
        ];
    }

    /**
     * Getter for $_defaultColumns. Scans recursively by hierarchy for defaultColumns
     * and caches to $_defaultColumns
     */
    static public function getDefaultColumns () {
        if (is_array(static::$_defaultColumns)) {
            return static::$_defaultColumns;
        };
        $columns = static::defaultColumns();
        $parent  = (new \ReflectionClass(get_called_class()))->getParentClass();
        if ($parent->hasMethod('getDefaultColumns')) {
            $columns = array_merge(call_user_func([$parent->getName(), 'getDefaultColumns']), $columns);
        };

        return static::$_defaultColumns = $columns;
    }

    /**
     * Returns column from $_defaultColumns
     */
    public function column ($name, array $config = []) {
        $column = static::getDefaultColumns()[$name];

        return is_array($column) ? array_merge($column, $config) : null;
    }

    /** @inheritdoc */
    protected function createDataColumn ($text) {
        $column = static::column($text);
        if (is_array($column)) {
            $column['attribute'] = $column['attribute'] ?: $text;

            return $this->createColumnObject($column);
        };

        return parent::createDataColumn($text);
    }

    /** @inheritdoc */
    public function run () {
        $tableClass = Yii::$app->themeManager->theme->settings->cssClassProvider('table_condensed', 'table-condensed');
        $this->tableOptions['class'] = sprintf('%s %s', $this->tableOptions['class'], $tableClass);
        parent::run();
        $this->registerClientScript();
    }

    private function registerClientScript () {
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
