<?php
namespace app\modules\server\models;

use Yii;

class Osimage extends \frontend\components\hiresource\ActiveRecord {
    /**
     * @return array the list of attributes for this record
     */
    public function attributes () {
        return [
            'osimage',
            'os',
            'version',
            'bitwise',
            'panel',
            'softpack'
        ];
    }

    public function rules () {
        return [
            [
                ['osimage'],
                'required'
            ]
        ];
    }

    /**
     * @return string
     */
    public function getFullOsName() {
        return implode(' ', [$this->os, $this->version, $this->bitwise]);
    }

    /**
     * @return bool
     */
    public function hasSoftPack() {
        return !empty($this->softpack);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels () {
        return [
            'osimagae' => Yii::t('app', 'System name of image'),
            'os'       => Yii::t('app', 'OS'),
            'version'  => Yii::t('app', 'Version'),
            'bitwise'  => Yii::t('app', 'Bitwise'),
            'panel'    => Yii::t('app', 'Panel'),
            'softpack' => Yii::t('app', 'Soft package'),

        ];
    }
}
