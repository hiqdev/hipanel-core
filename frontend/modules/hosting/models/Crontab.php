<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace frontend\modules\hosting\models;

use frontend\components\Model;
use frontend\components\ModelTrait;
use Yii;

class Crontab extends Model
{

    use ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['id', 'account_id', 'server_id', 'client_id'],    'integer'],
            [['crontab', 'account', 'server', 'client'],        'safe'],
            [['state', 'state_label'],                          'safe'],
            [['exists'],                                        'boolean'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return $this->mergeAttributeLabels([]);
    }
}
