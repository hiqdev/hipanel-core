<?php
declare(strict_types=1);

namespace hipanel\grid;

use hipanel\models\TaggableInterface;
use hipanel\widgets\TagsReadOnly;
use Yii;

class TagsReadOnlyColumn extends TagsColumn
{
    public function init()
    {
        parent::init();
    }

    public function getDataCellValue($model, $key, $index)
    {
        return TagsReadOnly::widget([
            'model' => $model,
        ]);
    }
}
