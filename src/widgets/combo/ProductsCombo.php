<?php

namespace hipanel\widgets\combo;

use hipanel\helpers\ArrayHelper;
use hiqdev\combo\Combo;

class ProductsCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'ref/products';

    /** {@inheritdoc} */
    public $name = 'name';

    /** {@inheritdoc} */
    public $url = '/ref/index';

    /** {@inheritdoc} */
    public $_return = ['id'];

    /** {@inheritdoc} */
    public $_rename = ['text' => 'name'];

    /** {@inheritdoc} */
    public function getFilter()
    {
        return ArrayHelper::merge(parent::getFilter(), [
            'gtype'  => ['format' => 'type,costprice_fraction'],
        ]);
    }
}
