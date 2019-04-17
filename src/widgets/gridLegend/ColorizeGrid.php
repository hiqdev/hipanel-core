<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets\gridLegend;

use Yii;
use yii\base\InvalidConfigException;

trait ColorizeGrid
{
    public $colorize = false;

    public function init()
    {
        parent::init();
        $this->colorizeRows();
    }

    /**
     * @param array $column
     * @return array
     */
    protected function colorizeColumns(array $column): array
    {
        if ($this->colorize) {
            $contentOptions = $column['contentOptions'] ?? [];
            $column['contentOptions'] = function ($model) use ($column, $contentOptions) {
                $coloredStyle = GridLegend::create(
                    $this->findOrFailGridLegend($model)
                )->gridColumnOptions($column['attribute']);
                $coloredStyle = $coloredStyle['style'] ?? '';
                $contentOptions['style'] .= $coloredStyle;

                return $contentOptions;
            };
        }

        return $column;
    }

    protected function colorizeRows()
    {
        if ($this->colorize) {
            $this->rowOptions = function ($model) {
                return GridLegend::create($this->findOrFailGridLegend($model))->gridRowOptions();
            };
        }
    }

    protected function createDataColumnByConfig(array $config = [])
    {
        return parent::createDataColumnByConfig($this->colorizeColumns($config));
    }

    protected function buildGridLegendClassName()
    {
        return sprintf('\hipanel\modules\%s\grid\%sGridLegend', Yii::$app->controller->module->id, ucfirst(Yii::$app->controller->id));
    }

    protected function findGridLegend($model)
    {
        $girdLegend = $this->buildGridLegendClassName();

        if (!class_exists($girdLegend)) {
            return null;
        }

        return new $girdLegend($model);
    }

    protected function findOrFailGridLegend($model)
    {
        $girdLegend = $this->findGridLegend($model);
        if ($girdLegend === null) {
            throw new InvalidConfigException('GridLegend class "' . $this->buildGridLegendClassName() . '" does not exist');
        }

        return $girdLegend;
    }
}
