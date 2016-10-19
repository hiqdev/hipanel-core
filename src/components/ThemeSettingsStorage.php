<?php

namespace hipanel\components;

use hiqdev\thememanager\storage\SettingsStorageInterface;
use Yii;
use yii\base\Component;
use yii\base\Model;
use yii\caching\Cache;

class ThemeSettingsStorage extends Component implements SettingsStorageInterface
{
    /**
     * @return SettingsStorage
     */
    protected function getStorage()
    {
        return Yii::$app->get('settingsStorage');
    }

    /**
     * @var string
     */
    public $cacheKey = ['themeSettingsStorage'];

    public function init()
    {
        if ($this->cacheKey === null && !Yii::$app->user->getIsGuest()) {
            $this->cacheKey = ['themeSettingsStorage', Yii::$app->user->id];
        }
    }

    /**
     * @return Cache
     */
    protected function getCache()
    {
        return Yii::$app->get('cache');
    }

    /**
     * @inheritdoc
     */
    public function set(Model $model)
    {
        if (Yii::$app->user->getIsGuest()) {
            return;
        }

        $data = $model->toArray();

        $this->getStorage()->setBounded('theme', $data);
        $this->getCache()->set($this->cacheKey, $data, 86400); // 1 day
    }

    /**
     * @return mixed
     */
    public function get()
    {
        if (Yii::$app->user->getIsGuest()) {
            return [];
        }

        if (($cache = $this->getCache()->get($this->cacheKey)) !== false) {
            return $cache;
        }

        $storage = $this->getStorage()->getBounded('theme');
        $this->getCache()->set($this->cacheKey, $storage, 86400); // 1 day

        return $storage;
    }
}
