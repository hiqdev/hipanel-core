<?php

namespace frontend\components\grid;

use yii\helpers\ArrayHelper;

class CheckboxColumn extends \yii\grid\CheckboxColumn
{
    use FeaturedColumnTrait;

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
