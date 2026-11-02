<?php

declare(strict_types=1);

namespace hipanel\actions;

use Yii;

/**
 * Keeps the total count an index page answers to IndexAction::VARIANT_TOTAL_COUNT_RESPONSE,
 * per user and URL, so a widget that asks for it can render it with its page instead.
 */
final class TotalCountCache
{
    private const DURATION = 180;

    public static function get(string $url): ?int
    {
        $count = Yii::$app->cache->get(self::buildKey($url));

        return $count === false ? null : (int)$count;
    }

    public static function getOrSet(string $url, callable $count): int
    {
        return (int)Yii::$app->cache->getOrSet(self::buildKey($url), $count, self::DURATION);
    }

    private static function buildKey(string $url): array
    {
        return [
            Yii::$app->user->getId(),
            $url,
            Yii::$app->request->getHostName(),
            __CLASS__,
        ];
    }
}
