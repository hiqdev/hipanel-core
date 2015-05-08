<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use hiqdev\hiar\HiResException;
use Yii;
use hiqdev\hiar\Collection;
use yii\base\InvalidConfigException;

/**
 * Class SwitchAction
 *
 * @property Collection|mixed collection
 * @package hipanel\actions
 */
class SwitchAction extends ActionManager
{
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
     * @var Collection|array the options that will be used to create the collection
     */
    protected $_collection;

    public function init()
    {

    }

    public function getItemConfig($name = null, array $config = [])
    {
        return [
            'class'  => 'hipanel\actions\SwitchRule',
            'name'   => $name,
            'parent' => $this,
            'action' => $config,
        ];
    }

    /**
     * Setter for the collection
     *
     * @param array $collection config for the collection
     */
    public function setCollection($collection)
    {
        $this->_collection = $collection;
    }

    /**
     * Gets the instance of the collection
     *
     * @return Collection
     */
    public function getCollection()
    {
        if (!is_object($this->_collection)) {
            $this->_collection = Yii::createObject(array_merge([
                'class'    => 'hiqdev\hiar\Collection',
                'model'    => $this->controller->newModel(),
                'scenario' => $this->controller->action,
            ], $this->_collection));
        }
        return $this->_collection;
    }

    public function run()
    {
        foreach ($this->keys() as $k) {
            $rule = $this->getItem($k);
            if ($rule->isApplicable()) {
                $error = $rule->perform();
                if ($error === false) {
                    return $this->success();
                }
            }
        }

        throw new InvalidConfigException('Broken SwitchAction, no applicable rule found');
    }

    public function perform ($rule) {
        if ($this->success) {

        }
        return $this->controller->runAction($rule->id);
    }
//
//    public function perform ($rule) {
//        if (!$this->perform()) {
//            return false;
//        }
//
//        $this->collection->load();
//
//        try {
//            $save = $this->collection->save();
//        } catch (HiResException $e) {
//            $save = $e->getMessage();
//        }
//    }

}