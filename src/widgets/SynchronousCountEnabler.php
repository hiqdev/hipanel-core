<?php
declare(strict_types=1);

namespace hipanel\widgets;

use hiqdev\hiart\ActiveDataProvider;

final class SynchronousCountEnabler
{
    private ActiveDataProvider $dataProvider;

    public function __construct(ActiveDataProvider $dataProvider)
    {
        $this->dataProvider = $this->prepareDataProvider($dataProvider);
    }

    private function prepareDataProvider(ActiveDataProvider $dataProvider): ActiveDataProvider
    {
        $dataProvider->refresh();
        $dataProvider->enableSynchronousCount();

        return clone $dataProvider;
    }

    public function getDataProvider(): ActiveDataProvider
    {
        return $this->dataProvider;
    }
}
