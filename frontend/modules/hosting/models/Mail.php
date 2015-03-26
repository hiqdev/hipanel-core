<?php
namespace frontend\modules\hosting\models;

use Yii;

class Mail extends \frontend\components\Model
{

    use \frontend\components\ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['id', 'hdomain_id', 'client_id', 'account_id', 'server_id'],          'integer'],
            [['mail', 'nick', 'hdomain', 'client', 'account', 'server', 'domain'],  'safe'],
            [['type', 'state', 'state_label'],                                      'safe'],
            [['forwards', 'spam_action', 'autoanswer', 'du_limit'],                 'safe'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return $this->margeAttributeLabels([
            'hdomain'               => Yii::t('app', 'Domain Name'),
            'domain'                => Yii::t('app', 'Domain Name'),
            'forwards'              => Yii::t('app', 'Forward'),
            'spam_action'           => Yii::t('app', 'Spam action'),
            'du_limit'              => Yii::t('app', 'Disck usage limit'),
        ]);
    }
}
