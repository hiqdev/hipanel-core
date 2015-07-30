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
     * @var string scenario to be used when save
     */
    public $_scenario;

    /**
     * @param string $scenario
     */
    public function setScenario($scenario)
    {
        $this->_scenario = $scenario;
    }

    /**
     * @return string
     */
    public function getScenario()
    {
        return $this->_scenario ?: ($this->parent ? $this->parent->getScenario() : $this->id);
    }

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
            if ($action instanceof Action) {
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

    /**
     * @var callable the custom callback to load data into the collection. Gets [[$this]] as the only argument
     * Should call `$this->collection->load()`
     */
    public $collectionLoader;

    /**
     * Loads data to the [[collection]]
     *
     * @param array $data
     */
    public function loadCollection($data = null) {
        if ($this->collectionLoader instanceof \Closure) {
            call_user_func($this->collectionLoader, $this);
        } else {
            $this->collection->load($data);
        }
    }

    /**
     * Saves stored [[collection]]
     *
     * @return bool
     */
    public function saveCollection() {
        if ($this->beforeSave instanceof \Closure) {
            call_user_func($this->beforeSave, $this);
        }
        return $this->collection->save();
    }

    /**
     * @var callable before save callback will be called before saving
     */
    public $beforeSave;

    /**
     * Performs action.
     *
     * @param SwitchRule $rule
     * @return boolean|string Whether save is success
     *  - boolean true or sting - an error
     *  - false - no errors
     */
    public function perform()
    {
        $this->loadCollection();

        try {
            $error = !$this->saveCollection();

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

    public function getModel()
    {
        return $this->parent ? $this->parent->getModel() : $this->collection->first;
    }

    public function getModels()
    {
        return $this->parent ? $this->parent->getModels() : $this->collection->models;
    }

    /**
     * @inheritdoc
     */
    public function getUniqueId()
    {
        return $this->parent !== null ? $this->parent->getUniqueId() : $this->controller->getUniqueId() . '/' . $this->id;
    }

    /**
     * Adds flash message
     *
     * @param string $type the type of flash
     * @param string $error the text of error
     */
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
