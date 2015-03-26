<?php
namespace frontend\modules\hosting\models;

use frontend\components\Model;
use frontend\components\ModelTrait;
use Yii;

class Backuping extends Model
{

    use ModelTrait;

    /** @inheritdoc */
    public function rules () {
        return [
            [['id', 'service_id', 'server_id', 'account_id', 'client_id'],                          'integer'],
            [['skip_lock'],                                                                         'boolean'],
            [['day','hour', 'path', 'include', 'exclude'],                                          'safe'],
            [['method', 'method_label','server', 'account', 'client', 'name', 'object', 'service'], 'safe'],
            [['backup_last' ],                                                                      'date'],
            [['backup_count', 'total_du', 'total_du_gb',],                                          'integer'],
            [['type', 'type_label', 'state', 'state_label'],                                        'safe'],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels () {
        return $this->margeAttributeLabels([
            'day'                   => Yii::t('app', 'Date'),
            'hour'                  => Yii::t('app', 'Time'),
            'skip_lock'             => Yii::t('app', 'Skip lock'),
            'backup_last'           => Yii::t('app', 'Last backup'),
            'backup_count'          => Yii::t('app', 'Count'),
            'total_du'              => Yii::t('app', 'Total of backup uses'),
            'total_du_gb'           => Yii::t('app', 'Total of backup uses in GB'),
            'method_label'          => Yii::t('app', 'Method label'),
        ]);
    }
}
