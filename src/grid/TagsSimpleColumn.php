<?php
declare(strict_types=1);

namespace hipanel\grid;

use hipanel\models\TaggableInterface;
use hipanel\widgets\TagsClient;
use Yii;

class TagsSimpleColumn extends TagsColumn
{
    public function init()
    {
        parent::init();
    }

    public function getDataCellValue($model, $key, $index)
    {
        return TagsClient::widget([
            'model' => $model,
        ]);
    }
}
