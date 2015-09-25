<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;

/**
 * Class FormatController
 * Used to format values with Yii formatter
 *
 * @package frontend\controllers
 */
class FormatController extends Controller {

    /**
     * Formats [[$value]] as currency.
     * See [[Formatter::asCurrency]] documentation
     *
     * @param $value
     * @param null $currency
     * @param array $options
     * @param array $textOptions
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    function actionCurrency($value, $currency = null, $options = [], $textOptions = []) {
        return Yii::$app->formatter->asCurrency($value, $currency, $options, $textOptions);
    }
}