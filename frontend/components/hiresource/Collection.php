<?php

namespace frontend\components\hiresource;

use common\components\Err;
use yii\base\Component;
use yii\base\InvalidValueException;
use yii\base\ModelEvent;

class Collection extends Component
{
    const EVENT_BEFORE_INSERT   = 'beforeInsert';
    const EVENT_BEFORE_UPDATE   = 'beforeUpdate';
    const EVENT_BEFORE_VALIDATE = 'beforeValidate';
    const EVENT_AFTER_VALIDATE  = 'afterValidate';
    const EVENT_AFTER_SAVE      = 'afterSave';

    /**
     * @var array
     */
    public $models;

    /**
     * @var string
     */
    public $formName;

    /**
     * @var string
     */
    public $formClass;

    /**
     * @var string
     */
    public $scenario;

    /**
     * @var ActiveRecord
     */
    public $first;

    /**
     * @var array
     */
    public $attributes;

    public function load (array $models) {
        /* @var $first ActiveRecord */
        $first = reset($models);
        if ($first === false) {
            return $this;
        }
        $this->first     = $first;
        $this->formName  = $first->formName();
        $this->formClass = $first->className();
        $this->models    = $models;
        if (!$this->isConsistent()) {
            throw new InvalidValueException('Models are not objects of same class or not follow same operation');
        }

        return $this;
    }

    public function save ($runValidation = true, $attributes = null, $options = []) {
        if ($this->first->getIsNewRecord()) {
            return $this->insert($runValidation, $attributes, $options);
        } else {
            return $this->update($runValidation, $attributes, $options);
        }
    }

    public function insert ($runValidation = true, $attributes = null, $options = []) {
        if ($attributes === null && $this->attributes) {
            $attributes = $this->attributes;
        }
        if ($runValidation && !$this->validate($attributes)) {
            return false;
        }
        if (!$this->beforeSave(true)) {
            return false;
        }

        $data    = $this->collectData($attributes, $options);
        $command = $this->first->getScenarioCommand('create', true);

        $results = $this->first->getDb()->createCommand()->perform($command, $data);

        if (Err::isError($results)) {
            throw new HiResException('Hiresource method: Insert -- ' . Json::encode($results), Err::getError($results));
        }

        $pk = $this->first->primaryKey()[0];
        foreach ($this->models as $key => $model) {
            /* @var $model ActiveRecord */
            $values     = &$data[$key];
            $result     = &$results[$key];
            $model->$pk = $result['id'];
            if ($pk != 'id') {
                $values[$pk] = $result['id'];
            }
            $changedAttributes = array_fill_keys(array_keys($values), null);
            $model->setOldAttributes($values);
            $model->afterSave(true, $changedAttributes);
        }

        $this->afterSave(true);

        return true;
    }

    public function update ($runValidation = true, $attributes = null, $options = []) {
        if ($attributes === null && $this->attributes) {
            $attributes = $this->attributes;
        }
        if ($runValidation && !$this->validate($attributes)) {
            return false;
        }
        if (!$this->beforeSave()) {
            return false;
        }

        $data    = $this->collectData($attributes, $options);
        $command = $this->first->getScenarioCommand('update', true);

        $result = $this->first->getDb()->createCommand()->perform($command, $data);

        if ($result === false || Err::isError($result)) {
            return false;
        }

        foreach ($this->models as $key => $model) {
            $changedAttributes = [];
            $values            = &$data[$key];
            foreach ($values as $name => $value) {
                /* @var $model ActiveRecord */
                $changedAttributes[$name] = $model->getOldAttribute($name);
                $model->setOldAttribute($name, $value);
            }
            $model->afterSave(false, $changedAttributes);
        }

        $this->afterSave();

        return true;
    }


    public function collectData ($attributes = null, $options = []) {
        $data = [];
        foreach ($this->models as $model) {
            /* @var $model ActiveRecord */
            $key = $model->getPrimaryKey();
            if (is_callable($options)) {
                $row = call_user_func($options, $model->getAttributes($attributes), $model);
            } else {
                $row = array_merge($model->getAttributes($attributes), $options);
            }

            if ($key) {
                $data[$key] = $row;
            } else {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function validate ($attributes = null) {
        if (!$this->beforeValidate()) {
            return false;
        }

        $this->first->validateMultiple($this->models, $attributes);

        $this->afterValidate();

        return true;
    }

    public function beforeValidate () {
        $event = new ModelEvent();
        foreach ($this->models as $model) {
            /* @var $model ActiveRecord */
            $model->scenario = $this->scenario;
        }

        $this->trigger(self::EVENT_BEFORE_VALIDATE, $event);

        return $event->isValid;
    }

    public function afterValidate () {
        $event = new ModelEvent();

        $this->trigger(self::EVENT_AFTER_VALIDATE, $event);

        return $event->isValid;
    }

    public function beforeSave ($insert = false) {
        $event = new ModelEvent();
        $this->trigger($insert ? self::EVENT_BEFORE_INSERT : self::EVENT_BEFORE_UPDATE, $event);

        return $event->isValid;
    }

    public function afterSave () {
        $this->trigger(self::EVENT_AFTER_SAVE);
    }

    public function triggerAll ($name, ModelEvent $event = null) {
        if ($event == null) {
            $event = new ModelEvent();
        }
        foreach ($this->models as $model) {
            /* @var $model ActiveRecord */
            $model->trigger($name, $event);
        }

        return $event->isValid;
    }

    public function isConsistent () {
        $new       = $this->first->getIsNewRecord();
        $className = $this->first->className();
        foreach ($this->models as $model) {
            /* @var $model ActiveRecord */
            if ($new != $model->getIsNewRecord() || $className != $model->className()) {
                return false;
            }
        }

        return true;
    }
}
