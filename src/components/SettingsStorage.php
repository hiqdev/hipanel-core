<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\components;

use hipanel\modules\client\models\Client;
use yii\base\Application;
use yii\base\Component;

class SettingsStorage extends Component implements SettingsStorageInterface
{
    /**
     * @var Application
     */
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    public function setGlobal($key, $value)
    {
        $this->perform('set-settings-storage', array_merge([
            'key' => $key,
            'data' => json_encode($value),
            'oauthClientBound' => false,
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function setBounded($key, $value)
    {
        $this->perform('set-settings-storage', array_merge([
            'key' => $key,
            'data' => json_encode($value),
            'oauthClientBound' => true,
        ]));
    }

    /**
     * {@inheritdoc}
     */
    public function getGlobal($key)
    {
        $response = $this->perform('get-settings-storage', array_merge([
            'key' => $key,
            'oauthClientBound' => false,
        ]));

        return $this->decodeResponse($response);
    }

    /**
     * {@inheritdoc}
     */
    public function getBounded($key)
    {
        $response = $this->perform('get-settings-storage', array_merge([
            'key' => $key,
            'oauthClientBound' => true,
        ]));

        return $this->decodeResponse($response);
    }

    /**
     * Performs request to the API.
     * @param string $key
     * @param array $value
     * @return array
     */
    private function perform($action, $data)
    {
        if ($this->app->user->isGuest) {
            return [];
        }

        try {
            return Client::perform($action, $data);
        } catch (\Throwable $exception) {
            \Yii::error('Failed to access client storage: ' . $exception->getMessage());
            return [];
        }
    }

    private function decodeResponse($response)
    {
        $result = json_decode($response['data'] ?? '{}', true);

        return $result === null ? [] : $result;
    }
}
