<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace frontend\modules\finance\models;

use Yii;

class Bill extends \frontend\components\Model
{

    use \frontend\components\ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['id'],                                    'safe'],
            [['seller_id','client_id'],                 'safe'],
            [['seller','client'],                       'safe'],
            [['sum','balance'],                         'safe'],
            [['quantity'],                              'safe'],
            [['type','gtype','currency'],               'safe'],
            [['label','descr','object','type_label'],   'safe'],
            [['time'],                                  'safe'],
            [['txn'],                                   'safe'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return $this->mergeAttributeLabels([
            'descr'                 => Yii::t('app', 'Description'),
        ]);
    }
}
