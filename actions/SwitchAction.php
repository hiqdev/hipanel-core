<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use hiqdev\hiart\HiResException;
use hiqdev\hiart\Collection;
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
     * @var
     */
    public $_scenario;

    /**
     * @var SwitchRule instance of the running rule
     */
    public $rule;

    public function init()
    {

    }

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

                $error  = $this->perform($rule);
                $type   = $error ? 'error' : 'success';
                if ($rule->save) {
                    $this->addFlash($type, $error);
                }
                $result = $rule->run($type);

                $this->rule = $oldRule;
                return $result;
            }
        }

        throw new InvalidConfigException('Broken SwitchAction, no applicable rule found');
    }

    /**
     * @param $rule
     * @return boolean|string Whether save is success
     *  - boolean true or sting - an error
     *  - false - no errors
     */
    public function perform($rule)
    {
        if (!$rule->save) {
            return false;
        }

        $this->collection->load();

        try {
            $error = !$this->collection->save();

            if ($error === true && $this->collection->hasErrors()) {
                $error = $this->collection->getFirstError();
            }
        } catch (HiResException $e) {
            $error = $e->getMessage();
        } catch (InvalidCallException $e) {
            $error = $e->getMessage();
        }
        return $error;
    }

    /**
     * @return mixed
     */
    public function getScenario()
    {
        return !empty($this->_scenario) ? $this->_scenario : $this->id;
    }

    /**
     * @param mixed $scenario
     */
    public function setScenario($scenario)
    {
        $this->_scenario = $scenario;
    }

    public function addFlash($type, $error = null)
    {
        if ($type == 'error' && is_string($error) && !empty($error)) {
            $text = Yii::t('app', $error);
        } else {
            $text = $this->{$type};
        }

        if ($type instanceof \Closure) {
            $text = call_user_func($text, $text, $this);
        }

        Yii::$app->session->addFlash($type, [
            'text' => $text
        ]);
    }
}
