<?php


namespace hipanel\components;


use hiqdev\hiart\Exception;

/**
 * Class I18nResponseErrorFormatterInterface
 * @package hipanel\components
 */
interface I18nResponseErrorFormatterInterface
{
    /**
     * @param Exception $exception
     * @return string
     */
    public function __invoke(Exception $exception): string;
}
