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

use hipanel\base\ModelTrait;
use Yii;

/**
 * Class File
 * @package hipanel\models
 */
class File extends \hiqdev\hiart\ActiveRecord
{
    use ModelTrait;

    public function rules()
    {
        return [
            [['id', 'type_id', 'state_id', 'client_id', 'seller_id', 'object_id', 'size'], 'integer'],
            [['filename', 'md5', 'type', 'state', 'client', 'seller'], 'safe'],
            [['create_time', 'update_time', 'month'], 'safe'],

            [['url', 'filename'], 'string', 'on' => ['put']],
        ];
    }
}
