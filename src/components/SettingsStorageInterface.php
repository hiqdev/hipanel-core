<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\components;

/**
 * Interface SettingsStorageInterface.
 */
interface SettingsStorageInterface
{
    /**
     * Sets $value by the $key to the user's storage.
     *
     * @param $key
     * @param $value
     * @void
     */
    public function setGlobal($key, $value);

    /**
     * Sets $value by the $key to the user's storage bound to the OAuth `client_id`.
     *
     * @param string $key
     * @param mixed $value
     * @void
     */
    public function setBounded($key, $value);

    /**
     * Gets value from user's storage $key.
     *
     * @param string $key
     * @return mixed Empty array will be returned if no data exists.
     */
    public function getGlobal($key);

    /**
     * Gets value from user's storage bound to the OAuth `client_id`.
     *
     * @param string $key
     * @return mixed Empty array will be returned if no data exists.
     */
    public function getBounded($key);
}
