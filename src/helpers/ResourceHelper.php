<?php

namespace hipanel\helpers;

use hipanel\models\Resource;
use hiqdev\php\units\Quantity;
use hiqdev\php\units\Unit;
use Yii;
use yii\helpers\ArrayHelper;

class ResourceHelper
{
    public static function getColumns(string $objectName): array
    {
        $map = [
            'server' => [
                'server_traf' => Yii::t('hipanel', 'Traffic Out'),
                'server_traf_in' => Yii::t('hipanel', 'Traffic In'),
                'server_traf95' => Yii::t('hipanel', 'Traffic 95 Out'),
                'server_traf95_in' => Yii::t('hipanel', 'Traffic 95 In'),
            ],
        ];

        return $map[$objectName];
    }

    public static function getFilterColumns(string $objectName): array
    {
        $columns = [];
        foreach (self::getColumns($objectName) as $type => $label) {
            $columns['overuse,' . $type] = $label;
        }

        return $columns;
    }

    public static function aggregateByObject(array $resources): array
    {
        $result = [];
        foreach ($resources as $resource) {
            $object = [
                'type' => $resource['type'],
                'unit' => 'gb',
            ];
            $object['amount'] += self::convert('byte', 'gb', $resource['total']);
            $result[$resource['object_id']][$resource['type']] = $object;
        }

        return $result;
    }

    public static function convert(string $from, string $to, $value)
    {
        return Quantity::create(Unit::create($from), $value)->convert(Unit::create($to))->getQuantity();
    }

    /**
     * @param Resource[] $models
     * @return array
     */
    public static function groupResourcesForChart(array $models): array
    {
        $labels = [];
        $data = [];
        ArrayHelper::multisort($models, 'date');
        foreach ($models as $model) {
            $labels[$model->date] = $model;
            $data[$model->type][] = $model->getDisplayAmount();
        }
        foreach ($labels as $date => $model) {
            $labels[$date] = $model->getDisplayDate();
        }

        return [$labels, $data];
    }
}
