<?php

namespace frontend\modules\hosting\models;

use frontend\components\helpers\ArrayHelper;
use frontend\components\SearchModelTrait;
use Yii;

/**
 * GallerySearch represents the model behind the search form about `app\models\Gallery`.
 */
class AccountSearch extends Account
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    public function searchAttributes() {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'with_request',
            'with_mail_settings',
            'with_counters'
        ]);
    }
}
