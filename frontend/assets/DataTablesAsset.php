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
    // public $sourcePath = '@vova07/themes/admin';

    /**
     * @inheritdoc
     */
    public $css = [
        'adminlte/css/datatables/dataTables.bootstrap.css'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'frontend\assets\AppAsset'
    ];
}