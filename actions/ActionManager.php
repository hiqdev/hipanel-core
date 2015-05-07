<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

class ActionManager extends Action implements \ArrayAccess, \IteratorAggregate, \yii\base\Arrayable

{
    use \hiqdev\collection\ManagerTrait;
}
