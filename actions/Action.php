<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use Yii;
use hiqdev\hiart\Collection;
use yii\helpers\ArrayHelper;

/**
 * HiPanel basic action.
 *
 * @property Collection collection
*/
class Action extends \yii\base\Action
{
    /**
     * @var Action|SwitchAction parent called action
     */
    public $parent;

    /**
     * @var Collection|array the options that will be used to create the collection.
     * Stores collection after creating
     */
    protected $_collection;

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
        if ($this->parent) {
            return $this->parent->getCollection();
        }

        if (!is_object($this->_collection)) {
            $action = $this->controller->action;
            if ($action instanceof SwitchAction) {
                $scenario = $action->getScenario();
            } else {
                $scenario = $action->id;
            }

            $this->_collection = Yii::createObject(ArrayHelper::merge([
                'class'    => 'hiqdev\hiart\Collection',
                'model'    => $this->controller->newModel(),
                'scenario' => $scenario,
            ], $this->_collection));
        }
        return $this->_collection;
    }

    public function getModel()
    {
        // TODO: getting multiple models
        return $this->parent ? $this->parent->getModel() : $this->collection->first;
    }

    /** @inheritdoc */
    public function getUniqueId()
    {
        return $this->parent !== null ? $this->parent->getUniqueId() : $this->controller->getUniqueId() . '/' . $this->id;
    }
}
