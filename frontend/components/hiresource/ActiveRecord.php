<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\components\hiresource;

use Yii;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\db\BaseActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\helpers\StringHelper;

class ActiveRecord extends BaseActiveRecord
{
    public $gl_key;
    public $gl_value;
    /**
     * Returns the database connection used by this AR class.
     * By default, the "hiresoruce" application component is used as the database connection.
     * You may override this method if you want to use a different database connection.
     * @return Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return \Yii::$app->get('hiresource');
    }

    /**
     * @inheritdoc
     * @return ActiveQuery the newly created [[ActiveQuery]] instance.
     */
    public static function find()
    {
        return Yii::createObject(ActiveQuery::className(), [get_called_class()]);
    }

    /**
     * @inheritdoc
     */
    public static function findOne($condition)
    {
        $query = static::find();
        if (is_array($condition)) {
            return $query->andWhere($condition)->one();
        } else {
            return static::get($condition);
        }

    }

    /**
     * Gets a record by its primary key.
     *
     * @param mixed $primaryKey the primaryKey value
     * @param array $options options given in this parameter are passed to elasticsearch
     * as request URI parameters.
     * Please refer to the [elasticsearch documentation](http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/docs-get.html)
     * for more details on these options.
     * @return static|null The record instance or null if it was not found.
     */
    public static function get($primaryKey, $options = [])
    {
        if ($primaryKey === null) {
            return null;
        }
        $command = static::getDb()->createCommand();
        $result = $command->get(static::type(), $primaryKey, $options);
        if ($result) {
            $model = static::instantiate($result);
            static::populateRecord($model, $result);
            $model->afterFind();
            return $model;
        }
        return null;
    }

    /**
     * This method defines the attribute that uniquely identifies a record.
     *
     * The primaryKey for elasticsearch documents is the `_id` field by default. This field is not part of the
     * ActiveRecord attributes so you should never add `_id` to the list of [[attributes()|attributes]].
     *
     * You may override this method to define the primary key name when you have defined
     * [path mapping](http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/mapping-id-field.html)
     * for the `_id` field so that it is part of the `_source` and thus part of the [[attributes()|attributes]].
     *
     * Note that elasticsearch only supports _one_ attribute to be the primary key. However to match the signature
     * of the [[\yii\db\ActiveRecordInterface|ActiveRecordInterface]] this methods returns an array instead of a
     * single string.
     *
     * @return string[] array of primary key attributes. Only the first element of the array will be used.
     */
    public static function primaryKey()
    {
        return ['id'];
    }

    /**
     * Returns the list of all attribute names of the model.
     *
     * This method must be overridden by child classes to define available attributes.
     *
     * Attributes are names of fields of the corresponding elasticsearch document.
     * The primaryKey for elasticsearch documents is the `_id` field by default which is not part of the attributes.
     * You may define [path mapping](http://www.elasticsearch.org/guide/en/elasticsearch/reference/current/mapping-id-field.html)
     * for the `_id` field so that it is part of the `_source` fields and thus becomes part of the attributes.
     *
     * @return string[] list of attribute names.
     * @throws \yii\base\InvalidConfigException if not overridden in a child class.
     */
    public function attributes()
    {
        throw new InvalidConfigException('The attributes() method of elasticsearch ActiveRecord has to be implemented by child classes.');
    }

    /**
     * A list of attributes that should be treated as array valued when retrieved through [[ActiveQuery::fields]].
     *
     * If not listed by this method, attributes retrieved through [[ActiveQuery::fields]] will converted to a scalar value
     * when the result array contains only one value.
     *
     * @return string[] list of attribute names. Must be a subset of [[attributes()]].
     */
    public function arrayAttributes()
    {
        return [];
    }

    /**
     * @return string the name of the index this record is stored in.
     */
    public static function index()
    {
        return Inflector::pluralize(Inflector::camel2id(StringHelper::basename(get_called_class()), '-'));
    }
    /**
     * @return string the name of the type of this record.
     */
    public static function type()
    {
        return Inflector::camel2id(StringHelper::basename(get_called_class()), '-');
    }


    public function insert($runValidation = true, $attributes = null, $options = [])
    {
        if ($runValidation && !$this->validate($attributes)) {
            return false;
        }

        if (!$this->beforeSave(true)) {
            return false;
        }

        $values = $this->getDirtyAttributes($attributes);
        // \yii\helpers\VarDumper::dump($values, 10, true);die();
        $response = static::getDb()->createCommand()->insert(
                          static::type(),
                          $values,
                          $this->getPrimaryKey(),
                          $options
        );

        $pk = static::primaryKey()[0];
        $this->$pk = $response['id'];
        if ($pk != 'id') {
            $values[$pk] = $response['id'];
        }
        $changedAttributes = array_fill_keys(array_keys($values), null);
        $this->setOldAttributes($values);
        $this->afterSave(true, $changedAttributes);
        return true;
    }

    public function delete($options = [])
    {
        if (!$this->beforeDelete()) {
            return false;
        }

        try {
            $result = static::getDb()->createCommand()->delete(
                            static::type(),
                            $this->getOldPrimaryKey(false),
                            $options
            );
        } catch(Exception $e) {
            // HTTP 409 is the response in case of failed optimistic locking
            // http://www.elasticsearch.org/guide/en/elasticsearch/guide/current/optimistic-concurrency-control.html
            if (isset($e->errorInfo['responseCode']) && $e->errorInfo['responseCode'] == 409) {
                throw new StaleObjectException('The object being deleted is outdated.', $e->errorInfo, $e->getCode(), $e);
            }
            throw $e;
        }
        $this->setOldAttributes(null);
        $this->afterDelete();
        if ($result === false) {
            return 0;
        } else {
            return 1;
        }
    }
}
