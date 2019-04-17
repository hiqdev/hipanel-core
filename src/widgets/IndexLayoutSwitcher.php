<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use hipanel\helpers\Url;
use hipanel\models\IndexPageUiOptions;
use yii\base\Widget;
use yii\bootstrap\ButtonGroup;
use yii\helpers\Html;

class IndexLayoutSwitcher extends Widget
{
    /**
     * @var IndexPageUiOptions
     */
    public $uiModel;

    public function run()
    {
        return ButtonGroup::widget([
            'encodeLabels' => false,
            'buttons' => [
                Html::a(
                    '<i class="fa fa-pause" aria-hidden="true"></i>',
                    Url::current(['orientation' => IndexPageUiOptions::ORIENTATION_HORIZONTAL]),
                    ['class' => 'btn btn-default btn-sm ' .
                            $this->getClassActive(IndexPageUiOptions::ORIENTATION_HORIZONTAL),
                    ]),
                Html::a(
                    '<i class="fa fa-pause fa-rotate-90" aria-hidden="true"></i>',
                    Url::current(['orientation' => IndexPageUiOptions::ORIENTATION_VERTICAL]),
                    ['class' => 'btn btn-default btn-sm ' .
                            $this->getClassActive(IndexPageUiOptions::ORIENTATION_VERTICAL),
                    ]),
            ],
        ]);
    }

    /**
     * @param string $orientation
     * @return string
     */
    private function getClassActive(string $orientation): string
    {
        return $this->isOriented($orientation) ? 'active' : '';
    }

    /**
     * @param $orientation
     * @return bool
     */
    private function isOriented(string $orientation): bool
    {
        return $this->uiModel->orientation === $orientation;
    }
}
