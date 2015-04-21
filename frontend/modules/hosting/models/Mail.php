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

class Mail extends Model
{

    use ModelTrait;

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
        return $this->mergeAttributeLabels([
            'hdomain'               => Yii::t('app', 'Domain Name'),
            'domain'                => Yii::t('app', 'Domain Name'),
            'forwards'              => Yii::t('app', 'Forward'),
            'spam_action'           => Yii::t('app', 'Spam action'),
            'du_limit'              => Yii::t('app', 'Disck usage limit'),
        ]);
    }
}
