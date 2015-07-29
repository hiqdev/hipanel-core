<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use hiqdev\hiart\HiResException;
use hiqdev\hiart\Collection;
use yii\base\Exception;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use Yii;

/**
 * Class SwitchAction
 *
 * @property Collection|mixed collection
 * @package hipanel\actions
 */
class SwitchAction extends Action implements \ArrayAccess, \IteratorAggregate, \yii\base\Arrayable
{
    use \hiqdev\collection\ManagerTrait;

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

    public function getItemConfig($name = null, array $config = [])
    {
        return [
            'class'   => 'hipanel\actions\SwitchRule',
            'name'    => $name,
            'switch'  => $this,
            'save'    => ArrayHelper::remove($config, 'save'),
            'success' => ArrayHelper::remove($config, 'success', $config),
            'error'   => ArrayHelper::remove($config, 'error'),
        ];
    }

    public function run()
    {
        foreach ($this->keys() as $k) {
            $rule = $this->getItem($k);
            if ($rule instanceof SwitchRule && $rule->isApplicable()) {
                $oldRule    = $this->rule;
                $this->rule = $rule;
                $error      = $this->perform($rule);
                $type       = $error ? 'error' : 'success';
                if ($rule->save) {
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
     * Performs action for the rule
     *
     * @param SwitchRule $rule
     * @return boolean|string Whether save is success
     *  - boolean true or sting - an error
     *  - false - no errors
     */
    public function perform($rule)
    {
        if (!$rule->save) {
            return false;
        }

        return parent::perform();
    }

}
