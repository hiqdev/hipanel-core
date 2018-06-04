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

use Yii;
use yii\helpers\Json;

class DateTimePicker extends \dosamigos\datetimepicker\DateTimePicker
{
    public function init()
    {
        $this->options['data-hiqdev-datetimepicker'] = uniqid();
        parent::init();
        $this->language = Yii::$app->language === 'en' ? null : Yii::$app->language;
        unset($this->options['readonly']);
    }

    public function registerClientScript()
    {
        $options = !empty($this->clientOptions) ? Json::encode($this->clientOptions) : '';
        $this->getView()->registerJs("
            if (!window.hiqdev_datetimepicker_options)
                window.hiqdev_datetimepicker_options = [];
            window.hiqdev_datetimepicker_options['{$this->options['data-hiqdev-datetimepicker']}'] = {$options};
        ");
        parent::registerClientScript();
    }
}
