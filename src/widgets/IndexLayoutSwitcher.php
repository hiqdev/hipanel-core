<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use hipanel\base\OrientationStorage;
use Yii;
use yii\base\Widget;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Html;

class IndexLayoutSwitcher extends Widget
{
    public function run()
    {
        return ButtonGroup::widget([
            'encodeLabels' => false,
            'buttons' => [
                Html::a(
                    '<i class="fa fa-pause" aria-hidden="true"></i>',
                    ['set-orientation', 'orientation' => OrientationStorage::ORIENTATION_HORIZONTAL, 'route' => Yii::$app->controller->getRoute()],
                    ['class' => 'btn btn-default btn-sm ' . ($this->isOrientation(OrientationStorage::ORIENTATION_HORIZONTAL) ? 'active' : '')]),
                Html::a(
                    '<i class="fa fa-pause fa-rotate-90" aria-hidden="true"></i>',
                    ['set-orientation', 'orientation' => OrientationStorage::ORIENTATION_VERTICAL, 'route' => Yii::$app->controller->getRoute()],
                    ['class' => 'btn btn-default btn-sm ' . ($this->isOrientation(OrientationStorage::ORIENTATION_VERTICAL) ? 'active' : '')]),
            ],
        ]);
    }

    /**
     * @param $orientation
     * @return bool
     */
    private function isOrientation($orientation)
    {
        return Yii::$app->get('orientationStorage')->get(Yii::$app->controller->route) === $orientation;
    }
}
