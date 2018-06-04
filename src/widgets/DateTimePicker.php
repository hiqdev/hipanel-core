<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use yii\helpers\Json;

class DateTimePicker extends \dosamigos\datetimepicker\DateTimePicker
{
    public function init()
    {
        $this->options['data-hiqdev-datetimepicker'] = 'datetimepicker_options';
        parent::init();
        $this->language = \Yii::$app->language === 'en' ? null : \Yii::$app->language;
        unset($this->options['readonly']);
    }

    public function registerClientScript()
    {
        $options = !empty($this->clientOptions) ? Json::encode($this->clientOptions) : '';
        ($this->getView())->registerJs("window.datetimepicker_options = {$options};");
        parent::registerClientScript();
    }
}
