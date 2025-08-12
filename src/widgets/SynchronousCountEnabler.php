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
    private ActiveDataProvider $dataProvider;
    private Closure $renderContent;

    public function __construct(ActiveDataProvider $dataProvider, Closure $renderContent)
    {
        $this->dataProvider = $this->prepareDataProvider($dataProvider);
        $this->renderContent = $renderContent;
    }

    private function prepareDataProvider(ActiveDataProvider $dataProvider): ActiveDataProvider
    {
        $dataProvider->refresh();
        $dataProvider->enableSynchronousCount();

        return clone $dataProvider;
    }

    public function __invoke(): string
    {
        $this->applyModelLoadingStrategy($this->dataProvider);
        $this->applyCachedTotalCount($this->dataProvider);

        $gridView = $this->createGridView($this->dataProvider);

        return call_user_func($this->renderContent, $gridView);
    }

    private function applyModelLoadingStrategy(ActiveDataProvider $dataProvider): void
    {
        $pageSize = $this->pageSize($dataProvider);
        $emptyModels = $this->createEmptyModels($pageSize);
        $emptyKeys = $this->createEmptyKeys($pageSize);

        $dataProvider->setModels($emptyModels);
        $dataProvider->setKeys($emptyKeys);
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
        $dataProvider->pagination->totalCount = $this->fetchCachedTotalCount($dataProvider);
    }

    private function fetchCachedTotalCount(ActiveDataProvider $dataProvider): int
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

    private function pageSize(ActiveDataProvider $dataProvider): int
    {
        $pageSize = $dataProvider->pagination->pageSize;
        $totalCount = $this->fetchCachedTotalCount($dataProvider);

        return min($pageSize, $totalCount);
    }

    public function getDataProvider(): ActiveDataProvider
    {
        return $this->dataProvider;
    }
}
