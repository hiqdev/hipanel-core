<?php

namespace hipanel\components;

use yii\web\UrlRuleInterface;

/**
 * Extends Yii2 UrlManager with two optimizations for hipanel's 3-segment routes
 * (e.g. finance/bill/view) that never match any URL rule and always use fallback.
 *
 * 1. canBeCached() returns true for all rules (including ROUTE_MISMATCH), so
 *    the main loop populates _ruleCache on the first call per cacheKey.
 *
 * 2. Tracks cacheKeys where ALL cached rules returned false ("fallback routes").
 *    On subsequent calls for these cacheKeys, getUrlFromCache returns false
 *    immediately without iterating the cached rules at all.
 */
class UrlManager extends \yii\web\UrlManager
{
    /** @var array cacheKeys where every rule failed — use fallback URL immediately */
    private array $_fallbackCacheKeys = [];
    /** @var array cacheKeys that have at least one rule added via setRuleToCache */
    private array $_populatedCacheKeys = [];

    protected function canBeCached(UrlRuleInterface $rule): bool
    {
        return true;
    }

    protected function setRuleToCache($cacheKey, UrlRuleInterface $rule): void
    {
        parent::setRuleToCache($cacheKey, $rule);
        $this->_populatedCacheKeys[$cacheKey] = true;
    }

    protected function getUrlFromCache($cacheKey, $route, $params)
    {
        if (isset($this->_fallbackCacheKeys[$cacheKey])) {
            return false;
        }

        $url = parent::getUrlFromCache($cacheKey, $route, $params);

        if ($url === false && isset($this->_populatedCacheKeys[$cacheKey])) {
            $this->_fallbackCacheKeys[$cacheKey] = true;
        }

        return $url;
    }
}
