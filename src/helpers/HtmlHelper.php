<?php

namespace hipanel\helpers;

use Yii;
use yii\web\JsExpression;

/**
 * Class HtmlHelper
 * @inheritdoc
 */
class HtmlHelper extends \yii\helpers\BaseHtml
{
    /**
     * Methods adds `data-loading-text` and `onClick` to options for [[Html::tag]] method in order to use easier
     * Bootstrap Button plugin.
     *
     * Example of use:
     *
     * ```php
     * Html::a('Click me', ['test/action'], HtmlHelper::loadingButtonOptions(['class' => 'test']);
     *
     * // <a href="/test/action" class="test" data-loading-text="loading" onClick="$(this).button('loading');">Click me</a>
     * ```
     *
     * @param array $buttonOptions
     * @return array
     * @see http://getbootstrap.com/javascript/#buttons
     */
    public static function loadingButtonOptions($buttonOptions = [])
    {
        return array_merge([
            'data-loading-text' => '<i class="fa fa-circle-o-notch fa-spin"></i> ' . Yii::t('hipanel', 'loading'),
            'onClick' => new JsExpression("$(this).button('loading');")
        ], (array)$buttonOptions);
    }
}
