<?php
/**
 * @link    http://hiqdev.com/hipanel-module-hosting
 * @license http://hiqdev.com/hipanel-module-hosting/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use hiqdev\hiar\Collection;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * HiPanel basic action.
 *
 * @property Collection collection
*/
class Action extends \yii\base\Action
{
    /**
     * @var Action parent called action
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
        if (!is_object($this->_collection)) {
            $this->_collection = Yii::createObject(ArrayHelper::merge([
                'class'    => 'hiqdev\hiar\Collection',
                'model'    => $this->controller->newModel(),
                'scenario' => $this->controller->action,
            ], $this->_collection));
        }
        return $this->_collection;
    }

    public function getModel()
    {
        // TODO: getting multiple models
        return $this->collection->first;
    }

    /** @inheritdoc */
    public function getUniqueId()
    {
        return $this->parent !== null ? $this->parent->getUniqueId() : $this->controller->getUniqueId() . '/' . $this->id;
    }

    public function run()
    {
        d('base Action');
    }
}
