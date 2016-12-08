<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\models;

class Status extends \hiqdev\hiart\ActiveRecord
{
    use \hipanel\base\ModelTrait;

    public function rules()
    {
        return [
            [['id', 'object_id', 'subject_id', 'type_id'], 'integer'],
            [['type', 'subject_name'], 'safe'],
        ];
    }
}
