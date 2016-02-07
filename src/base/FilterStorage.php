<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\base;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

/**
 * Class FilterStorage
 * The class manages the GridView filters storage to save filters between pages.
 * Usage example:.
 *
 * ```php
 * $filterStorage = new FilterStorage([
 *    'map' => [
 *        'login' => 'client.login', // Means `login` search model attribute's value will be saved to `client.login`
 *    ]
 * ]);
 *
 * $filters = $filterStorage->get(); // Gets the array of filters according to the map, listed above
 * $filterStorage->set(Yii::$app->request->get('MyModel', [])); // Updates saved filters from the GET request.
 * ```
 */
class FilterStorage extends Component
{
    /**
     * @var array[] The array of filters map
     *
     * Example of usage is listed in the description of the class.
     * @see FilterStorage
     */
    public $map = [];

    /**
     * Collects the filters according to the [[map]].
     *
     * @throws InvalidConfigException
     * @return array key-value pairs of attributes and filters
     * @see map
     */
    public function get()
    {
        $result = [];

        foreach ($this->map as $attribute => $key) {
            $value = $this->getByKey($key);
            $result[$attribute] = $value;
        }

        return $result;
    }

    /**
     * Sets the filters to the storage according to the [[map]].
     *
     * @param $filters
     * @throws InvalidConfigException
     * @see map
     */
    public function set($filters)
    {
        $filters = (array) $filters;

        foreach ($this->map as $attribute => $key) {
            if (!isset($filters[$attribute])) {
                continue;
            }

            $value = $filters[$attribute];
            $this->setByKey($key, $value);
        }
    }

    /**
     * Clears filters for all the attributes, described in the [[map]].
     *
     * @void
     */
    public function clearFilters()
    {
        foreach ($this->map as $key) {
            $this->setByKey($key, null);
        }
    }

    /**
     * Saves the $value as the $key.
     * If the value is an empty string or null, the $key will be removed from the storage.
     *
     * @param $key
     * @param $value
     * @void
     */
    public function setByKey($key, $value)
    {
        $storage = $this->getStorage();
        if ($value !== '' && $value !== null) {
            $storage[$key] = $value;
        } else {
            unset($storage[$key]);
        }
        $this->setStorage($storage);
    }

    /**
     * Returns a value by a specific $key.
     *
     * @param $key
     * @return null|mixed
     */
    public function getByKey($key)
    {
        $storage = $this->getStorage();
        return isset($storage[$key]) ? $storage[$key] : null;
    }

    /**
     * Returns the array from the filter storage.
     * @return array
     */
    public function getStorage()
    {
        return Yii::$app->session->get('filterStorage', []);
    }

    /**
     * Saves the $storage to the session.
     *
     * @param array $storage
     */
    public function setStorage(array $storage = [])
    {
        Yii::$app->session->set('filterStorage', $storage);
    }
}
