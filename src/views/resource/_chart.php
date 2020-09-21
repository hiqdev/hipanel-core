<?php
/**
 * Server module for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-server
 * @package   hipanel-module-server
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2019, HiQDev (http://hiqdev.com/)
 */

use hipanel\modules\server\widgets\ResourceConsumption;
use hipanel\modules\server\widgets\TrafficConsumption;

$options = [
    'id' => 'widget_id_tc_' . $consumptionBase,
    'labels' => $labels,
    'data' => $data,
    'consumptionBase' => $consumptionBase,
];
echo in_array($consumptionBase, ['server_traf', 'server_traf95'], true) ? TrafficConsumption::widget($options) : ResourceConsumption::widget($options);
