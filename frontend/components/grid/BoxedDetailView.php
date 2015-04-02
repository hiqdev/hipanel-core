<?php

namespace frontend\components\grid;

use frontend\components\widgets\Box;

class BoxedDetailView extends DetailView
{
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
