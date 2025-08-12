<?php

declare(strict_types=1);

namespace hipanel\widgets;

use hiqdev\hiart\ActiveDataProvider;
use Yii;
use yii\base\DynamicModel;
use yii\grid\GridView;

class DataProviderGridRenderer
{
    private ActiveDataProvider $dataProvider;

    public function __construct(ActiveDataProvider $dataProvider)
    {
        $this->dataProvider = (new SynchronousCountEnabler($dataProvider))->getDataProvider();
    }

    public function renderPager(): string
    {
        $this->prepareDataProvider();
        $gridView = $this->createGridView($this->dataProvider);

        return $gridView->renderPager();
    }

    private function prepareDataProvider(): void
    {
        $this->applyEmptyModelsStrategy($this->dataProvider);
        $this->applyCachedTotalCount($this->dataProvider);
    }

    private function applyEmptyModelsStrategy(ActiveDataProvider $dataProvider): void
    {
        $pageSize = $this->calculatePageSize($dataProvider);
        $emptyModels = $this->createEmptyModels($pageSize);
        $emptyKeys = $this->createEmptyKeys($pageSize);

        $dataProvider->setModels($emptyModels);
        $dataProvider->setKeys($emptyKeys);
    }

    private function calculatePageSize(ActiveDataProvider $dataProvider): int
    {
        return min(
            $dataProvider->pagination->pageSize,
            $this->getCachedTotalCount($dataProvider)
        );
    }

    private function getCachedTotalCount(ActiveDataProvider $dataProvider): int
    {
        return Yii::$app->cache->getOrSet(
            $this->buildCacheKey($dataProvider),
            fn(): int => $dataProvider->getTotalCount(),
            5
        );
    }

    private function buildCacheKey(ActiveDataProvider $dataProvider): array
    {
        return [
            Yii::$app->user->getId(),
            $dataProvider->query->modelClass,
            $dataProvider->query->where,
            Yii::$app->request->getHostName(),
            __METHOD__,
        ];
    }

    private function createEmptyModels(int $pageSize): array
    {
        return array_pad([], $pageSize, new DynamicModel());
    }

    private function createEmptyKeys(int $pageSize): array
    {
        return array_pad([], $pageSize, null);
    }

    private function applyCachedTotalCount(ActiveDataProvider $dataProvider): void
    {
        $dataProvider->pagination->totalCount = $this->getCachedTotalCount($dataProvider);
    }

    private function createGridView(ActiveDataProvider $dataProvider): GridView
    {
        return Yii::createObject([
            'class' => GridView::class,
            'dataProvider' => $dataProvider,
            // Disable guessing columns from models (don't load models)
            'columns' => [
                'dummy' => 'foo',
            ],
        ]);
    }

    public function renderSummary(): string
    {
        $this->prepareDataProvider();
        $gridView = $this->createGridView($this->dataProvider);

        return $gridView->renderSummary();
    }
}
