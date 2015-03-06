<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 06.03.15
 * Time: 13:46
 */

namespace frontend\components\grid;

class CheckboxColumn extends \yii\grid\CheckboxColumn
{
    /**
     * @var array
     */
    public $checkboxOptions = [
        'class' => 'check',
    ];
}