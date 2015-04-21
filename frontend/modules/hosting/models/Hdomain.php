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

class Hdomain extends Model
{

    use ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [
                ['id', 'server_id', 'client_id', 'seller_id', 'account_id', 'hdomain_id', 'state_id', 'type_id'],
                'integer'
            ],
            [['server', 'client', 'seller', 'account', 'hdomain', 'state', 'type', 'ip', 'alias'], 'safe'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return $this->mergeAttributeLabels([
            'hdomain'               => Yii::t('app', 'Domain Name'),
        ]);
    }
}
