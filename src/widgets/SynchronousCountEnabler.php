<?php

namespace hipanel\widgets;

use hiqdev\hiart\ActiveDataProvider;
use Yii;
use yii\base\DynamicModel;
use yii\base\Widget;
use yii\grid\GridView;

class SynchronousCountEnabler extends Widget
{
    public ActiveDataProvider $dataProvider;

    public $content;

    public function run()
    {
        $this->dataProvider->enableSynchronousCount();

        $dataProvider = clone $this->dataProvider;
        $dataProvider->setModels([new DynamicModel()]);
        $dataProvider->setKeys([null]);
        $dataProvider->setPagination(['totalCount' => $dataProvider->getTotalCount()]);

        $grid = Yii::createObject([
            'class' => GridView::class,
            'dataProvider' => $dataProvider,
        ]);

        return call_user_func($this->content, $grid);
    }
}
