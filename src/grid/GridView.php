<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;
use hipanel\assets\CheckboxStyleAsset;
use hipanel\helpers\ArrayHelper;
use hipanel\modules\client\grid\ClientColumn;
use hipanel\modules\client\grid\SellerColumn;
use hipanel\widgets\LinkSorter;
use hiqdev\assets\datatables\DataTablesAsset;
use Yii;

/**
 * Class GridView.
 * HiPanel specific GridView.
 */
class GridView extends \hiqdev\higrid\GridView
{
    public $sorter = [
        'class' => LinkSorter::class,
    ];

    /**
     * {@inheritdoc}
     */
    public $resizableColumns = [
        'resizeFromBody' => false,
    ];

    /**
     * {@inheritdoc}
     */
    public $options = [
        'class' => 'dataTables_wrapper form-inline',
        'role' => 'grid',
    ];

    /**
     * {@inheritdoc}
     */
    public $layout = "<div class='row'><div class='col-xs-12'>{sorter}</div></div><div class=\"table-responsive\">{items}</div>\n<div class='row'><div class='col-sm-6 col-xs-12'><div class='dataTables_info'>{summary}</div></div>\n<div class='col-sm-6 col-xs-12'><div class='dataTables_paginate paging_bootstrap'>{pager}</div></div></div>";

    /**
     * {@inheritdoc}
     */
    public function columns()
    {
        return array_merge(parent::columns(), [
            'seller_id' => [
                'class' => SellerColumn::class,
            ],
            'client_id' => [
                'class' => ClientColumn::class,
            ],
            'checkbox' => [
                'class' => CheckboxColumn::class,
            ],
            'seller' => [
                'class' => SellerColumn::class,
                'attribute' => 'seller_id',
            ],
            'client' => [
                'class' => ClientColumn::class,
                'attribute' => 'client_id',
            ],
            'client_like' => [
                'class' => ClientColumn::class,
                'filterOptions' => ['class' => 'narrow-filter'],
            ],
            'blocking' => [
                'label' => Yii::t('hipanel', 'Block'),
                'class' => BlockingColumn::class,
            ],
        ]);
    }

    /**
     * {@inheritdoc}
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
     * {@inheritdoc}
     */
    private function registerClientScript()
    {
        $view = $this->getView();
        DataTablesAsset::register($view);
        CheckboxStyleAsset::register($view);
    }
}
