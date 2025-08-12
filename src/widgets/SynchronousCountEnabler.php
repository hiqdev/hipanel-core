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
    private bool $shouldLoadModels = true;
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
        if ($this->shouldLoadModels) {
            return;
        }

        $pageSize = $dataProvider->pagination->pageSize;
        $emptyModels = array_pad([], $pageSize, new DynamicModel());
        $emptyKeys = array_pad([], $pageSize, null);

        $dataProvider->setModels($emptyModels);
        $dataProvider->setKeys($emptyKeys);
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
        ]);
    }

    public function preventModelsLoading(): self
    {
        $this->shouldLoadModels = false;

        return $this;
    }

    public function getDataProvider(): ActiveDataProvider
    {
        return $this->dataProvider;
    }
}
