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

use hiqdev\thememanager\models\OrientationInterface;
use Yii;
use yii\base\Component;

/**
 * Class UiOptionsStorage.
 */
class UiOptionsStorage extends Component implements OrientationInterface
{
    /**
     * @var string the cache key that will be used to cache storage values
     */
    public $cacheKey;

    /**
     * @var string
     */
    public $settingsStorageKey = 'uiOptions';

    /**
     * @var array
     */
    protected $storage;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        if (!isset($this->cacheKey)) {
            $this->cacheKey = ['uiOptionsStorage', Yii::$app->user->id];
        }
    }

    /**
     * @return \yii\caching\Cache
     */
    protected function getCache()
    {
        return Yii::$app->getCache();
    }

    /**
     * @return SettingsStorageInterface
     */
    protected function getSettingsStorage()
    {
        return Yii::$app->get('settingsStorage');
    }

    /**
     * Ensures that [[storage]] contains actual orientations storage.
     *
     * @return array
     */
    private function ensureStorage()
    {
        if (!isset($this->storage)) {
            $this->storage = $this->getCache()->get($this->cacheKey);

            if ($this->storage === false) {
                $this->storage = $this->getSettingsStorage()->getBounded($this->settingsStorageKey);
                $this->cache();
            }
        }

        return $this->storage;
    }

    /**
     * Saves current [[storage]].
     */
    protected function saveStorage()
    {
        $this->getSettingsStorage()->setBounded($this->settingsStorageKey, $this->storage);
        $this->cache();
    }

    /**
     * Caches current [[storage]].
     */
    protected function cache()
    {
        $this->getCache()->set($this->cacheKey, $this->storage, 86400); // 1 day
    }

    /**
     * Sets orientation for the $route.
     *
     * @param string $route
     * @param string $orientation
     */
    public function set($route, $options = [])
    {
        $this->ensureStorage();

        $this->storage[$route] = $options;
        $this->saveStorage();
    }

    /**
     * Gets orientation for the $route.
     *
     * @param $route
     * @return string
     */
    public function get($route)
    {
        $this->ensureStorage();

        return isset($this->storage[$route]) ? $this->storage[$route] : null;
    }

    /**
     * @return string
     */
    public function getDefaultOption()
    {
        $settings = Yii::$app->themeManager->getSettings();

        if (property_exists($settings, 'filterOrientation') && in_array($settings->filterOrientation, [self::ORIENTATION_HORIZONTAL, self::ORIENTATION_VERTICAL], true)) {
            return $settings->filterOrientation;
        } else {
            return self::ORIENTATION_HORIZONTAL;
        }
    }

    public function getOption($option)
    {
        return $this->get(Yii::$app->request->pathInfo)[$option];
    }

    public function getAllUiOptions()
    {
        return $this->getSettingsStorage()->getBounded($this->settingsStorageKey);
    }
}
