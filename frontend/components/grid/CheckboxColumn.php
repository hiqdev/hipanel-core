<?php

namespace frontend\components\grid;

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
