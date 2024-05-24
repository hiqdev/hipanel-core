<?php

declare(strict_types=1);

namespace hipanel\grid;

use hipanel\models\TaggableInterface;
use hipanel\widgets\TagsManager;
use Yii;

class TagsColumn extends DataColumn
{
    public $format = 'raw';
    public $attribute = 'tags';

    public function init(): void
    {
        $this->label = Yii::t('hipanel', 'Tags');
        $this->enableSorting = false;
        $this->visible = Yii::$app->user->can('owner-staff');
        $this->exportedValue = static function (TaggableInterface $model) {
            $output = [];
            if ($model->isTagsHidden()) {
                return '';
            }
            foreach ($model->tags as $tag) {
                $output[] = $tag;
            }

            return implode(', ', $output);
        };
        parent::init();
    }

    public function getDataCellValue($model, $key, $index)
    {
        return TagsManager::widget(['model' => $model]);
    }
}
