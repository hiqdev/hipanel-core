<?php
declare(strict_types=1);

namespace hipanel\widgets;

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
            'columns' => ['name:text', 'value:text'],
        ]);
    }
}
