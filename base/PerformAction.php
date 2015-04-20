<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\base;

class PerformAction extends \yii\base\Action
{
    public function run () {
        return $this->controller->perform(['scenario' => $this->id]);
    }
}
