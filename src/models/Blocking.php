<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\models;

use hiqdev\hiart\ActiveRecord;

class Blocking extends ActiveRecord
{
    public function rules()
    {
        return [
            [['client_id', 'seller_id'], 'integer'],
            [['reason', 'reason_label', 'comment', 'time', 'client', 'seller'], 'string'],
        ];
    }
}
