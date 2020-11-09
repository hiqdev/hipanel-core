<?php

namespace hipanel\widgets;

use hiqdev\hiart\ActiveDataProvider;
use Yii;
use yii\base\Widget;
use yii\grid\GridView;

class SynchronousCountEnabler extends Widget
{
    public ActiveDataProvider $dataProvider;

    public $content;

    public function run()
    {
        $this->dataProvider->enableSynchronousCount();
        $grid = Yii::createObject([
            'class' => GridView::class,
            'dataProvider' => $this->dataProvider,
        ]);

        return call_user_func($this->content, $grid);
    }
}
