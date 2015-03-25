<?php
namespace frontend\modules\hosting\models;

use Yii;

class Db extends \frontend\components\Model
{

    use \frontend\components\ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['id', 'account_id', 'client_id', 'service_id','server_id'],                           'integer'],
            [['name', 'account', 'client', 'service', 'server'],                                    'safe'],
            [['service_ip', 'description'],                                                         'safe'],
            [['type', 'state', 'backuping_type','type_label','state_label','backuping_type_label'], 'safe'],
            [['password'],                                                                          'safe'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return $this->margeAttributeLabels([
            'service_ip'            => Yii::t('app', 'Service IP'),
            'backuping_type'        => Yii::t('app', 'Type of backuping'),
            'backuping_type_label'  => Yii::t('app', 'Backuping type label'),
        ]);
    }
}
