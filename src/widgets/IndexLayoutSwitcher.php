<?php

namespace hipanel\widgets;

use yii\base\Widget;
use yii\bootstrap\ButtonGroup;

class IndexLayoutSwitcher extends Widget
{
    public function run()
    {
        return ButtonGroup::widget([
            'encodeLabels' => false,
            'buttons' => [
                [
                    'label' => '<i class="fa fa-pause" aria-hidden="true"></i>',
                    'options' => ['class' => 'btn btn-default btn-sm ' . $this->checkLayoutOrientation('horizontal')]
                ],
                [
                    'label' => '<i class="fa fa-pause fa-rotate-90" aria-hidden="true"></i>',
                    'options' => ['class' => 'btn btn-default btn-sm ' . $this->checkLayoutOrientation('vertical')]
                ],
            ]
        ]);
    }

    private function checkLayoutOrientation($orientation)
    {
        $activeClass = '';
        switch ($orientation) {
            case 'horizontal':
                $activeClass = 'active';
                break;
            case 'vertical':
                $activeClass = '';
                break;
        }

        return $activeClass;
    }
}