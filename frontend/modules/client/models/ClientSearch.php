<?php

namespace frontend\modules\client\models;

use frontend\components\helpers\ArrayHelper;
use frontend\components\SearchModelTrait;

class ClientSearch extends Client
{
    use SearchModelTrait {
        searchAttributes as defaultSearchAttributes;
    }

    public function searchAttributes() {
        return ArrayHelper::merge($this->defaultSearchAttributes(), [
            'client_like',
        ]);
    }
}
