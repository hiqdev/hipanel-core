<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\controllers;

use Yii;
use yii\web\Controller;

/**
 * Class FormatController
 * Used to format values with Yii formatter.
 */
class FormatController extends Controller
{
    /**
     * Formats [[$value]] as currency.
     * See [[Formatter::asCurrency]] documentation.
     *
     * @param $value
     * @param null $currency
     * @param array $options
     * @param array $textOptions
     * @throws \yii\base\InvalidConfigException
     * @return string
     */
    public function actionCurrency($value, $currency = null, $options = [], $textOptions = [])
    {
        return Yii::$app->formatter->asCurrency($value, $currency, $options, $textOptions);
    }
}
