<?php

namespace hipanel\components;

use hiqdev\hiart\Connection;
use yii\base\Component;
use yii\di\Instance;

class SettingsStorage extends Component implements SettingsStorageInterface
{
    /**
     * @var Connection
     */
    private $connection;

    public function init()
    {
        $this->connection = Instance::ensure('hiart', Connection::class);
    }

    /**
     * @inheritdoc
     */
    public function setGlobal($key, $value)
    {
        $this->perform('clientSetSettingsStorage', array_merge([
            'key' => $key,
            'data' => json_encode($value),
            'oauthClientBound' => false
        ]));
    }

    /**
     * @inheritdoc
     */
    public function setBounded($key, $value)
    {
        $this->perform('clientSetSettingsStorage', array_merge([
            'key' => $key,
            'data' => json_encode($value),
            'oauthClientBound' => true
        ]));
    }

    /**
     * @inheritdoc
     */
    public function getGlobal($key)
    {
        $response = $this->perform('clientGetSettingsStorage', array_merge([
            'key' => $key,
            'oauthClientBound' => false
        ]));

        return json_decode($response['data'], true);
    }

    /**
     * @inheritdoc
     */
    public function getBounded($key)
    {
        $response = $this->perform('clientGetSettingsStorage', array_merge([
            'key' => $key,
            'oauthClientBound' => true
        ]));

        return json_decode($response['data'], true);
    }

    /**
     * Performs request to the API
     *
     * @param string $key
     * @param array $value
     * @return array
     */
    private function perform($key, $value)
    {
        return $this->connection->createCommand()->perform($key, $value);
    }
}
