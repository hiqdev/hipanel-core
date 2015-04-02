<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 23.03.15
 * Time: 14:05
 */

namespace frontend\components\grid;

use frontend\components\widgets\Box;

class BoxedGridView extends GridView
{
    static public $detailViewClass = 'frontend\components\grid\BoxedDetailView';
    /**
     * To grid options, for example, you may add something like this for customize boxes:
     *  'boxOptions' => ['options' => ['class' => 'box-primary']],
     * @var array
     */
    public $boxOptions = [];

    public function run() {
        Box::begin($this->boxOptions);
            parent::run();
        Box::end();
    }
}
