<?php
declare(strict_types=1);

namespace hipanel\widgets;

use Closure;
use hiqdev\hiart\ActiveDataProvider;
use Yii;
use yii\base\DynamicModel;
use yii\grid\GridView;

final class SynchronousCountEnabler
{
    private bool $loadModels = true;
    private ActiveDataProvider $dataProvider;
    private Closure $renderContent;

    public function __construct(ActiveDataProvider $dataProvider, Closure $renderContent)
    {
        $dataProvider->refresh();
        $dataProvider->enableSynchronousCount();

        $this->dataProvider = clone $dataProvider;
        $this->renderContent = $renderContent;
    }

    public function __invoke(): string
    {
        $dataProvider = $this->dataProvider;

        if (! $this->loadModels) {
            $pageSize = $dataProvider->pagination->pageSize;
            $dataProvider->setModels(array_pad([], $pageSize, new DynamicModel()));
            $dataProvider->setKeys(array_pad([], $pageSize, null));
        }
        $dataProvider->pagination->totalCount = $this->getCachedTotalCount($dataProvider);

        $grid = Yii::createObject([
            'class' => GridView::class,
            'dataProvider' => $dataProvider,
        ]);

        return call_user_func($this->renderContent, $grid);
    }

    private function getCachedTotalCount(ActiveDataProvider $dataProvider): int
    {
        return Yii::$app->cache->getOrSet(
            [
                Yii::$app->user->getId(),
                $dataProvider->query->modelClass,
                $dataProvider->query->where,
                Yii::$app->request->getHostName(),
                __METHOD__,
            ],
            fn(): int => $dataProvider->getTotalCount(),
            5
        );
    }

    public function preventModelsLoading(): self
    {
        $this->loadModels = false;

        return $this;
    }

    public function getDataProvider(): ActiveDataProvider
    {
        return $this->dataProvider;
    }
}
