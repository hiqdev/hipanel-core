<?php
namespace frontend\modules\hosting\models;

use Yii;

class Ip extends \frontend\components\Model
{

    use \frontend\components\ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['id', 'client_id'],                       'integer'],
            [['ip','objects_count', 'tags', 'client'],  'safe'],
            [['prefix', 'family'],                      'safe'],
            [['type', 'state', 'state_label'],          'safe'],
            [['links', 'expanded_ips', 'ip_normalized'],'safe'],
            [['is_single'],                             'boolean'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return $this->margeAttributeLabels([
            'objects_count'         => Yii::t('app', 'Count of objects'),
            'is_single'             => Yii::t('app', 'Single'),
            'ip_normalized'         => Yii::t('app', 'Normalized IP'),
            'expanded_ips'          => Yii::t('app', 'Expanded IPs'),
        ]);
    }
}
