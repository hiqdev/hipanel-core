<?php

namespace hipanel\base;

use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\helpers\StringHelper;

/**
 * Class FilterStorage
 * The class manages the GridView filters storage to save filters between pages.
 * Usage example:
 *
 * ```php
 * $filterStorage = new FilterStorage([
 *    'map' => [
 *        'login' => 'client.login', // Means `login` search model attribute's value will be saved to `client.login`
 *        'domain_like' => [ // `domain_like` will be:
 *            'domain.name | hosting.domain.name', // read from `domain.name`, when empty - try from `hosting.domain.name`
 *            'domain.name' // saved to `domain.name`. It's not allowed to list more than one destination to save to.
 *        ],
 *        'state' => [
 *            function ($filterStorage, $attribute) { // the same as in previous example, but done with a closure
 *                return $filterStorage->getByKeys('domain.name | hosting.domain.name');
 *            },
 *            function ($value, $filterStorage, $attribute) { // it is possible to modify filter value before save
 *                return strtolower($value); // for example for normalization
 *            }
 *        ]
 *    ]
 * ]);
 *
 * $filters = $filterStorage->get(); // Gets the array of filters according to the map, listed above
 * $filterStorage->set(Yii::$app->request->get('MyModel', [])); // Updates saved filters from the GET request.
 * ```
 *
 * @package hipanel\base
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
     * Collects the filters according to the [[map]]
     *
     * @return array key-value pairs of attributes and filters
     * @throws InvalidConfigException
     * @see map
     */
    public function get()
    {
        $result = [];

        foreach ($this->map as $attribute => $key) {
            if (is_array($key)) {
                if (is_string($key[0])) {
                    $value = $this->getByKeys($key[0]);
                } elseif ($key[0] instanceof \Closure) {
                    $value = call_user_func($key[0], $this, $attribute);
                } else {
                    throw new InvalidConfigException('Filter getting keys MUST be strings or Closures only');
                }
            } else {
                $value = $this->getByKeys($key);
            }

            $result[$attribute] = $value;
        }

        return $result;
    }

    /**
     * Sets the filters to the storage according to the [[map]]
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

            $filter = $filters[$attribute];

            if (is_array($key)) {
                if (is_string($key[1])) {
                    $key = $key[1];
                    $value = $filter;
                } elseif ($key[1] instanceof \Closure) {
                    $value = call_user_func($key[1], $filter, $this, $attribute);
                } else {
                    throw new InvalidConfigException('Filter getting keys MUST be strings or Closures only');
                }
            } else {
                $value = $filter;
            }

            $this->setByKey($key, $value);
        }
    }

    /**
     * Saves the $value as the $key.
     * If the value is an empty string or null, the $key will be removed from the storage
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
     * Returns the value of filter by the keys list separated with `|`.
     * For example:
     *
     * ```
     * domain.name | hosting.domain.name
     * ```
     * The method will check `domain.name` key and return it's value, when it is set.
     * Otherwise, the method will check next tokens.
     * Method returns `null`, when all tokens are empty.
     *
     * @param string $keys
     * @return null|mixed
     */
    public function getByKeys($keys)
    {
        $storage = $this->getStorage();
        $keys = StringHelper::explode($keys, '|');

        foreach ($keys as $key) {
            if (isset($storage[$key])) {
                return $storage[$key];
            }
        }

        return null;
    }

    /**
     * Returns a value by a specific $key
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
     * Returns the array from the filter storage
     * @return array
     */
    public function getStorage()
    {
        return Yii::$app->session->get('filterStorage', []);
    }

    /**
     * Saves the $storage to the session
     *
     * @param array $storage
     */
    public function setStorage(array $storage = [])
    {
        Yii::$app->session->set('filterStorage', $storage);
    }
}
