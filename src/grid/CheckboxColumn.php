<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use hiqdev\higrid\FeaturedColumnTrait;

class CheckboxColumn extends \yii\grid\CheckboxColumn
{
    use FeaturedColumnTrait;

    public $attribute;

    /** {@inheritdoc} */
    public $defaultOptions = [
        'headerOptions' => [
            'style' => 'width:1em',
        ],
        'checkboxOptions' => [
            'class' => 'icheck',
        ],
    ];
}
