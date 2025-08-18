<?php

declare(strict_types=1);

namespace hipanel\widgets;

use hiqdev\hiart\ActiveDataProvider;
use Yii;
use yii\base\DynamicModel;
use yii\grid\GridView;
use yii\grid\SerialColumn;

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
        $pagination = $dataProvider->pagination;
        if ($pagination === false) {
            // No pagination
            return 0;
        }

        $pageSize = $pagination->pageSize;

        $totalCount = $this->getCachedTotalCount($dataProvider);
        $page = $this->getPage(); // 1-based index

        // Offset of first item on current page
        $offset = ($page - 1) * $pageSize;

        // If fewer total items than a single page
        if ($totalCount <= $pageSize) {
            return $totalCount;
        }

        // If last page
        if ($offset + $pageSize > $totalCount) {
            return $totalCount - $offset;
        }

        // Full page
        return $pageSize;
    }

    private function getPage(): int
    {
        // Because the page value form DataProvider pagination is always null
        return isset($_REQUEST['page']) ? max(1, (int)$_REQUEST['page']) : 1;
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
        if ($pageSize <= 0) {
            return [];
        }

        return array_map(
            static fn() => new DynamicModel(),
            array_fill(0, $pageSize, null)
        );
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
                ['class' => SerialColumn::class],
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
