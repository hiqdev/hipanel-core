<?php

namespace hipanel\widgets;

use hipanel\helpers\ResourceConfigurator;
use hipanel\models\IndexPageUiOptions;
use yii\base\ViewContextInterface;
use yii\base\Widget;
use yii\data\DataProviderInterface;

abstract class BaseResourceViewer extends Widget
{
    public DataProviderInterface $dataProvider;

    public ViewContextInterface $originalContext;

    public IndexPageUiOptions $uiModel;

    public ResourceConfigurator $configurator;
}