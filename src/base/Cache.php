<?php

namespace hipanel\base;

use yii\caching\Cache as yiiCache;
use ReflectionFunction;

class Cache extends \yii\caching\FileCache
{
    public function getTimeCached ($timeout, array $params, $func) {
        $key = array_merge([__CLASS__, ReflectionFunction::export($func, true)], $params);
        $res = $this->get($key);
        if ($res === false) {
            $res = call_user_func_array($func, $params);
            $this->set($key, $res, $timeout);
        }

        return $res;
    }

/* TRY to realize later
    public function init()
    {
        parent::init();
        if (!is_object($this->cache)) {
            $this->cache = Instance::ensure($this->cache, yiiCache::className());
        }
    }

    public function getTimeCached ($timeout, array $params, $func) {
        $key = array_merge([__CLASS__], $params);
        $res = $this->cache->get($key);
        if ($res === false) {
            $res = call_user_func_array($func, $params);
            $this->getCache()->set($key, $res, $timeout);
        }

        return $res;
    }
*/

}
