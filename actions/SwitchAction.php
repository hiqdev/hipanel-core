<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use Yii;
use yii\base\InvalidConfigException;

class SwitchAction extends Action implements \ArrayAccess, \IteratorAggregate, \yii\base\Arrayable
{
    use \hiqdev\collection\ManagerTrait;

    public $success;

    public $error;

    public $addFlash;

    public function getItemConfig($name = null, array $config = [])
    {
        return [
            'class'     => 'hipanel\actions\SwitchRule',
            'name'      => $name,
            'parent'    => $this,
            'action'    => $config,
        ];
    }

    public function run()
    {
        foreach ($this->keys() as $k) {
            $rule = $this->getItem($k);
            if ($rule->isApplicable()) {
                return $this->controller->runAction($rule->id);
            }
        }

        throw new InvalidConfigException('broken SwitchAction, no applicable rule found');
    }

}
