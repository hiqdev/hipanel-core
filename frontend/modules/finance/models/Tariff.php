<?php

namespace frontend\modules\finance\models;

use frontend\components\Model;
use Yii;

class Tariff extends Model
{
    use \frontend\components\ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['id'],                                    'safe'],
            [['seller_id','client_id'],                 'safe'],
            [['seller','client'],                       'safe'],
            [['name'],                                  'safe'],
            [['note','included_in'],                    'safe'],
            [['type','state'],                          'safe'],
            [['label','descr','type_label'],            'safe'],
            [['used'],                                  'safe'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return $this->margeAttributeLabels([
            'quantity'              => Yii::t('app', 'Quantity'),
            'time'                  => Yii::t('app', 'Time'),
            'label'                 => Yii::t('app', 'Label'),
            'descr'                 => Yii::t('app', 'Description'),
        ]);
    }
}
