<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace frontend\modules\server\models;

use Yii;

/**
 * @property string osimage
 * @property string os
 * @property string version
 * @property string bitwise
 * @property string panel
 * @property array softpack
 */
class Osimage extends \hiqdev\hiar\ActiveRecord
{
    /**
     * @return array the list of attributes for this record
     */
    public function attributes () {
        return ['osimage', 'os', 'version', 'bitwise', 'panel', 'softpack'];
    }

    public function rules () {
        return [[['osimage'], 'required']];
    }

    /**
     * @param string $delimiter defines delimiter to separeate os, version and bitwise of OS
     * @return string
     */
    public function getFullOsName ($delimiter = ' ') {
        return implode($delimiter, [$this->os, $this->version, $this->bitwise]);
    }

    public function getSoftPackName () { return $this->hasSoftPack() ? $this->softpack['name'] : 'clear'; }

    public function hasSoftPack () { return !empty($this->softpack); }

    public function getPanelName () { return $this->panel ?: 'no'; }

    public function getSoftPack () { return $this->hasSoftPack() ? $this->softpack : []; }

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
