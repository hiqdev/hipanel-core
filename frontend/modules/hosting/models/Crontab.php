<?php
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
            [['exists'],                                        'bool'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return $this->margeAttributeLabels([
            'id'                    => Yii::t('app', 'ID'),
            'account_id'            => Yii::t('app', 'Account ID'),
            'server_id'             => Yii::t('app', 'Server ID'),
            'client_id'             => Yii::t('app', 'Client ID'),
            'state_label'           => Yii::t('app', 'State label'),
        ]);
    }
}
