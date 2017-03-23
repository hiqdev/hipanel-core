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
    public $cacheKey;

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
     * {@inheritdoc}
     */
    public function set(Model $model)
    {
        if (Yii::$app->user->getIsGuest()) {
            return;
        }

        $data = $model->toArray();

        $this->getStorage()->setBounded('theme', $data);
        $this->setToCache($data);
    }

    /**
     * @return mixed
     */
    public function get()
    {
        if (Yii::$app->user->getIsGuest()) {
            return [];
        }

        if (($cached = $this->getFromCache()) !== false) {
            return $cached;
        }

        $storage = $this->getStorage()->getBounded('theme');
        $this->setToCache($storage);

        return $storage;
    }

    private function getFromCache()
    {
        if ($this->cacheKey === null) {
            return false;
        }

        return $this->getCache()->get($this->cacheKey);
    }

    private function setToCache($data)
    {
        if ($this->cacheKey === null) {
            return false;
        }

        return $this->getCache()->set($this->cacheKey, $data, 86400); // 1 day
    }
}
