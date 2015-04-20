<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\grid;

use yii\helpers\ArrayHelper;

class CheckboxColumn extends \yii\grid\CheckboxColumn
{
    use FeaturedColumnTrait;

    public $attribute;

    /** @inheritdoc */
    public $defaultOptions = [
        'headerOptions' => [
            'style' => 'width:1em',
        ],
        'checkboxOptions' => [
            'class' => 'icheck',
        ],
    ];

}
