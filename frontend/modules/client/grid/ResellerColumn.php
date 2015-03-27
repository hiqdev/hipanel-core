<?php

namespace frontend\modules\client\grid;

use frontend\components\widgets\Combo2;

class ResellerColumn extends ClientColumn
{
    public $attribute     = 'seller_id';
    public $nameAttribute = 'seller';
    public $clientType    = 'reseller';

    public function init () {
        parent::init();

        $this->filter = Combo2::widget([
            'type'                => 'reseller',
            'attribute'           => $this->attribute,
            'model'               => $this->grid->filterModel,
            'formElementSelector' => 'td',
        ]);
    }
}
