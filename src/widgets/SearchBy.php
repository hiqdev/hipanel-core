<?php declare(strict_types=1);

namespace hipanel\widgets;

enum SearchBy: string
{
    case LIKE = 'like';
    case ILIKE = 'ilike';
    case LIKEI = 'likei';
    case LEFT_LIKEI = 'leftLikei';

    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function array(): array
    {
        return array_combine(self::values(), self::names());
    }
}
