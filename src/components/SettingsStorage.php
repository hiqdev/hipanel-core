<?php

namespace hipanel\components;

use hiqdev\hiart\Connection;
use yii\base\Component;
use yii\di\Instance;
use Yii;

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

        return $this->decodeResponse($response);
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

        return $this->decodeResponse($response);
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
        if (Yii::$app->user->isGuest) {
            return [];
        }

        return $this->connection->createCommand()->perform($key, $value);
    }

    private function decodeResponse($response)
    {
        $result = json_decode(isset($response['data']) ? $response['data'] : '{}', true);
        return $result === null ? [] : $result;
    }
}
