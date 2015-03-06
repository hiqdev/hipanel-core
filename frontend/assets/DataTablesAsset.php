<?php
namespace frontend\assets;
use yii\web\AssetBundle;
/**
 * Theme data tables asset bundle.
 */
class DataTablesAsset extends AssetBundle
{

    /**
     * @inheritdoc
     */
     public $sourcePath = '@bower/admin-lte';

    /**
     * @inheritdoc
     */
    public $css = [
        'plugins/datatables/dataTables.bootstrap.css',
    ];

    public $js = [
//        'plugins/datatables/dataTables.bootstrap.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}