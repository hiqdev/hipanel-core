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

class Ip extends Model
{

    use ModelTrait;

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
        return $this->mergeAttributeLabels([
            'objects_count'         => Yii::t('app', 'Count of objects'),
            'is_single'             => Yii::t('app', 'Single'),
            'ip_normalized'         => Yii::t('app', 'Normalized IP'),
            'expanded_ips'          => Yii::t('app', 'Expanded IPs'),
        ]);
    }
}
