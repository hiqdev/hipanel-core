<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\helpers;

use yii\helpers\Inflector;

class Url extends \yii\helpers\Url
{
    /**
     * @param string|array $params
     * @return array|string
     */
    public static function toAction($link, $params = [], $action = 'index')
    {
        $params = is_array($params) ? $params : ['id' => $params];
        if (strpos('/', $link) === false) {
            $link = $link . '/' . $action;
        }
        if (strpos('@', $link) === false) {
            $link = '@' . $link;
        }
        array_unshift($params, $link);
        return $params;
    }

    /**
     * Build search url.
     *
     * @param string $modelName
     * @param array $params
     * @param string $action
     * @return array|string
     */
    public static function toSearch(string $modelName, array $params = [], string $action = 'index')
    {
        $formName = Inflector::id2camel($modelName) . 'Search';
        return static::toAction($modelName, [$formName => $params], $action);
    }
}
