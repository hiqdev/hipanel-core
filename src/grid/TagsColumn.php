<?php
declare(strict_types=1);

namespace hipanel\grid;

use hipanel\widgets\TagsManager;
use Yii;

class TagsColumn extends \yii\grid\DataColumn
{
    public $format = 'raw';
    public $attribute = 'tags';

    public function init()
    {
        $this->label = Yii::t('hipanel', 'Tags');
        $this->enableSorting = false;
        parent::init();
    }

    public function getDataCellValue($model, $key, $index)
    {
        return TagsManager::widget(['model' => $model]);
    }
}
