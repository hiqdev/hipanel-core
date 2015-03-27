<?php
namespace frontend\modules\domain\models;

use Yii;

class Domain extends \frontend\components\Model
{

    use \frontend\components\ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['id'],                                            'safe'],
            [['domain'],                                        'safe'],
            [['remoteid','epp_client_id'],                      'safe'],
            [['seller_id','client_id'],                         'safe'],
            [['seller','client'],                               'safe'],
            [['state','block','lastop'],                        'safe'],
            [['state_label'],                                   'safe'],
            [['registrant','admin','tech','billing'],           'safe'],
            [['whois_protected','is_secured','autorenewal'],    'safe'],
            [['is_holded','is_freezed','is_premium'],           'safe'],
            [['created_date','updated_date'],                   'safe'],
            [['transfer_date','expiration_date'],               'safe'],
            [['expires','since','operated'],                    'safe'],
            [['note'],                                          'safe', 'on' => ['set-note','default']],
            [['registrant','admin','tech','billing'],           'safe', 'on' => ['set-contacts']],
            [['enable'],                                        'safe', 'on' => ['set-lock','set-autorenewal','set-whois-protect']],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return $this->margeAttributeLabels([
            'epp_client_id'         => Yii::t('app', 'EPP client ID'),
            'remoteid'              => Yii::t('app', 'Remote ID'),
            'statuses'              => Yii::t('app', 'Statuses'),
            'zone_id'               => Yii::t('app', 'Zone ID'),
            'domain'                => Yii::t('app', 'Domain Name'),
            'note'                  => Yii::t('app', 'Notes'),
            'nameservers'           => Yii::t('app', 'Name Servers'),
            'created_date'          => Yii::t('app', 'Registered'),
            'updated_date'          => Yii::t('app', 'Update Time'),
            'transfer_date'         => Yii::t('app', 'Transfered'),
            'expiration_date'       => Yii::t('app', 'System Expiration Time'),
            'expires'               => Yii::t('app', 'Payed Till'),
            'since'                 => Yii::t('app', 'Since Time'),
            'lastop'                => Yii::t('app', 'Last Operation'),
            'operated'              => Yii::t('app', 'Last Operation Time'),
            'whois_protected'       => Yii::t('app', 'WHOIS'),
            'is_secured'            => Yii::t('app', 'Lock'),
            'is_holded'             => Yii::t('app', ' label'),
            'is_freezed'            => Yii::t('app', ' label'),
            'foa_sent_to'           => Yii::t('app', ' label'),
            'is_premium'            => Yii::t('app', ' label'),
            'prem_expires'          => Yii::t('app', ' label'),
            'prem_daysleft'         => Yii::t('app', ' label'),
            'premium_autorenewal'   => Yii::t('app', ' label'),
            'url_fwval'             => Yii::t('app', ' label'),
            'mailval'               => Yii::t('app', ' label'),
            'parkval'               => Yii::t('app', ' label'),
            'daysleft'              => Yii::t('app', ' label'),
            'is_expired'            => Yii::t('app', ' label'),
            'expires_soon'          => Yii::t('app', ' label'),
        ]);
    }
}
