<?php

namespace hipanel\components;

/**
 * Interface SettingsStorageInterface
 *
 * @package hipanel\components
 */
interface SettingsStorageInterface
{
    /**
     * Sets $value by the $key to the user's storage
     *
     * @param $key
     * @param $value
     * @void
     */
    function setGlobal($key, $value);

    /**
     * Sets $value by the $key to the user's storage bound to the OAuth `client_id`
     *
     * @param $key
     * @param $value
     * @void
     */
    function setBounded($key, $value);

    /**
     * Gets value from user's storage $key
     *
     * @param $key
     * @return array
     */
    function getGlobal($key);

    /**
     * Gets value from user's storage bound to the OAuth `client_id`
     *
     * @param $key
     * @return array
     */
    function getBounded($key);
}
