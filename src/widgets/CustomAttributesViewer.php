<?php
declare(strict_types=1);

namespace hipanel\widgets;

use hipanel\models\CustomAttribute;
use hiqdev\hiart\ActiveRecord;
use yii\base\Widget;
use yii\data\ArrayDataProvider;
use yii\grid\GridView;

final class CustomAttributesViewer extends Widget
{
    public ActiveRecord $owner;

    public function run(): string
    {
        return GridView::widget([
            'layout' => '{items}',
            'tableOptions' => ['class' => 'table table-striped', 'style' => 'margin: 0'],
            'showHeader' => false,
            'emptyText' => '',
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $this->owner->getCustomAttributes(),
                'sort' => false,
                'pagination' => false,
            ]),
            'columns' => [
                'name' => [
                    'attribute' => 'name',
                    'format' => 'text',
                    'value' => fn(CustomAttribute $model): string => $this->owner->getCustomAttributeName($model->name),
                ],
                'value' => [
                    'attribute' => 'value',
                    'value' => fn(CustomAttribute $model): string => $model->stringValue(),
                ]
            ],
        ]);
    }
}
