<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\assets;

class MomentAsset extends \omnilight\assets\MomentAsset
{
    public $js = [
        'moment-with-locales.min.js',
    ];

    /**
     * @param \yii\web\View $view
     */
    public function registerAssetFiles($view)
    {
        parent::registerAssetFiles($view);
        $view->registerJs(";moment.locale('" . \Yii::$app->language . "');");
    }
}
