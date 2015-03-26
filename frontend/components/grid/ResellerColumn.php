<?php

namespace frontend\components\grid;

class ResellerColumn extends ClientColumn
{
    public $attribute       = 'seller_id';
    public $nameAttribute   = 'seller';
    public $clientType      = 'reseller';
}
