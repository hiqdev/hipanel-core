<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 06.03.15
 * Time: 13:46
 */

namespace frontend\components\grid;
use yii\helpers\ArrayHelper;

class CheckboxColumn extends \yii\grid\CheckboxColumn
{
    /**
     * @var array
     */
    public $checkboxOptions = [
        'class' => 'icheck',
    ];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        /// XXX TODO REDO BETTER
        $this->headerOptions = ArrayHelper::merge([
            'style' => 'width:1em',
        ],$this->headerOptions);
    }

}
