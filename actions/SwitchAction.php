<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use hiqdev\hiar\HiResException;
use hiqdev\hiar\Collection;
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
     * @var string the success message
     */
    public $success;

    /**
     * @var string the error message
     */
    public $error;

    /**
     * @var boolean whether to add flash message
     */
    public $addFlash = true;

    /**
     * @var boolean whether to save data before running action
     */
    public $save = false;

    public function init()
    {

    }

    public function getItemConfig($name = null, array $config = [])
    {
        return [
            'class'   => 'hipanel\actions\SwitchRule',
            'name'    => $name,
            'switch'  => $this,
            'success' => ArrayHelper::remove($config, 'success', $config),
            'error'   => ArrayHelper::remove($config, 'error'),
        ];
    }

    public function run()
    {
        foreach ($this->keys() as $k) {
            $rule = $this->getItem($k);
            if ($rule->isApplicable()) {
                $error = $this->perform($rule);
                return $rule->runAction($error ? 'error' : 'success');
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
        $error = true;
        if (!$this->save) {
            return false;
        }

        $this->collection->load();

        try {
            $error = $this->collection->save();
        } catch (HiResException $e) {
            $error = $e->getMessage();
        } catch (InvalidCallException $e) {
            $error = $e->getMessage();
        }
        return $error;
    }

}
