<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

use hiqdev\hiart\Collection;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

/**
 * Class SwitchAction.
 *
 * @property Collection|mixed collection
 */
class SwitchAction extends Action implements \ArrayAccess, \IteratorAggregate, \yii\base\Arrayable
{
    use \hiqdev\yii2\collection\ManagerTrait;

    /**
     * @var string|callable the success message or a callback, that returns string.
     * Gets arguments
     */
    public $success;

    /**
     * @var string the error message
     */
    public $error;

    /**
     * @var SwitchRule instance of the current running rule
     */
    public $rule;

    /** {@inheritdoc} */
    public function init()
    {
        parent::init();

        $this->addItems($this->getDefaultRules());
    }

    /**
     * @return array the default rules for the action.
     * You can override this method in child classes to set own default rules.
     */
    protected function getDefaultRules()
    {
        return [];
    }

    public function getItemConfig($name = null, array $config = [])
    {
        return [
            'class'   => SwitchRule::class,
            'name'    => $name,
            'switch'  => $this,
            'save'    => ArrayHelper::remove($config, 'save'),
            'success' => ArrayHelper::remove($config, 'success', $config),
            'error'   => ArrayHelper::remove($config, 'error'),
            'flash'   => ArrayHelper::remove($config, 'flash', true),
        ];
    }

    public function run()
    {
        foreach ($this->keys() as $k) {
            $rule = $this->getItem($k);
            if ($rule instanceof SwitchRule && $rule->isApplicable()) {
                $oldRule    = $this->rule;
                $this->rule = $rule;
                $error      = $this->perform();
                $type       = $error ? 'error' : 'success';
                if ($rule->save && $rule->flash) {
                    $this->addFlash($type, $error);
                }
                $result     = $rule->run($type);
                $this->rule = $oldRule;

                return $result;
            }
        }

        throw new InvalidConfigException('Broken SwitchAction, no applicable rule found');
    }

    /**
     * Does perform only if rule has 'save' enabled.
     */
    public function perform()
    {
        if (!$this->rule->save) {
            return false;
        }

        return parent::perform();
    }
}
