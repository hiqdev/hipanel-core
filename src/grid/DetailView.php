<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use hiqdev\thememanager\ThemeManager;
use Yii;
use yii\helpers\Html;

/**
 * Class DetailView.
 */
class DetailView extends \hiqdev\higrid\DetailView
{
    public function init()
    {
        parent::init();

        if (Yii::$app->has('themeManager')) {
            /** @var ThemeManager $themeManager */
            $settings = Yii::$app->get('themeManager')->getTheme()->getSettings();
            if (isset($settings->table_condensed) && $settings->table_condensed) {
                Html::addCssClass($this->options, 'table-condensed');
            }
        }
    }
}
