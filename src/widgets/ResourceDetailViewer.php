<?php

namespace hipanel\widgets;

use hipanel\models\Resource;
use yii\base\ViewContextInterface;
use yii\base\Widget;
use yii\data\DataProviderInterface;

class ResourceDetailViewer extends Widget
{
    public DataProviderInterface $dataProvider;

    public ViewContextInterface $originalContext;

    public $originalModel;

    public $searchModel;

    public $uiModel;

    public function run(): string
    {
        $this->registerJs();

        return $this->render('ResourceDetailViewer', [
            'dataProvider' => $this->dataProvider,
            'originalContext' => $this->originalContext,
            'originalModel' => $this->originalModel,
            'uiModel' => $this->uiModel,
            'searchModel' => $this->searchModel,
            'model' => new Resource(),
        ]);
    }

    private function registerJs(): void
    {
        $this->view->registerJs(<<<'JS'
JS
        );
    }
}