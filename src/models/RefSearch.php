<?php
declare(strict_types=1);

namespace hipanel\models;

use hipanel\base\SearchModelTrait;
use hipanel\helpers\ArrayHelper;
use Yii;

class RefSearch extends Ref
{
    public const DEFAULT_SEARCH_ATTRIBUTE = 'type,bill';

    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    public function searchAttributes()
    {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'gtype',
            'select',
        ]);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            'gtype' => Yii::t('hipanel', 'Gtype'),
        ]);
    }
}
