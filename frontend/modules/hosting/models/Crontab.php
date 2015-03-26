<?php
namespace frontend\modules\hosting\models;

use Yii;

class Crontab extends \frontend\components\Model
{

    use \frontend\components\ModelTrait;

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
        return $this->margeAttributeLabels([]);
    }
}
