<?php

namespace frontend\modules\server\models;

use frontend\components\helpers\ArrayHelper;
use frontend\components\SearchModelTrait;
use Yii;

class ServerSearch extends Server
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    public function searchAttributes() {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'with_request',
        ]);
    }
}
