<?php

namespace hipanel\helpers;

use yii\helpers\Inflector;

class Url extends \yii\helpers\Url
{
    /**
     * @param string|array $params
     */
    static public function toAction($link, $params = [], $action = 'index')
    {
        $params = is_array($params) ? $params : ['id' => $params];
        if (strpos('/', $link) ===  false) {
            $link = $link . '/' . $action;
        }
        if (strpos('@', $link) ===  false) {
            $link = '@' . $link;
        }
        array_unshift($params, $link);
        return $params;
    }

    /**
     * Build search url
     */
    static public function toSearch($model, array $params = [], $action = 'index') {
        $formName = Inflector::id2camel($model).'Search';
        return static::toAction($model, [$formName => $params], $action);
    }
}
