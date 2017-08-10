<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use hipanel\widgets\Box;

class BoxedDetailView extends DetailView
{
    public $boxed = true;
    /**
     * To grid options, for example, you may add something like this for customize boxes:
     *  'boxOptions' => ['options' => ['class' => 'box-primary']],.
     * @var array
     */
    public $boxOptions = [];

    public function run()
    {
        if ($this->boxed) {
            Box::begin($this->boxOptions);
            echo parent::run();
            Box::end();
        } else {
            return parent::run();
        }
    }
}
