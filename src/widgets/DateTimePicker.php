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

class DateTimePicker extends \dosamigos\datetimepicker\DateTimePicker
{
    public function init()
    {
        parent::init();
        unset($this->options['readonly']);
    }
}
