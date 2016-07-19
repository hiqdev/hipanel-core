<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 11.02.15
 * Time: 17:59.
 */
namespace hipanel\behaviors;

use hipanel\models\File as FileModel;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class File extends Behavior
{
    /**
     * @var string the attribute which holds the attachment.
     */
    public $attribute = 'file';

    /**
     * @var array the scenarios in which the behavior will be triggered
     */
    public $scenarios = [];

    /**
     * @var string the attribute that will receive the file id
     */
    public $savedAttribute;

    /**
     * @var UploadedFile the uploaded file instance.
     */
    private $_file;

    /**
     * @return array
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'saveUploadedFile',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'saveUploadedFile',
        ];
    }

    /**
     * Event handler for beforeSave.
     * @param \yii\base\ModelEvent|boolean $event
     */
    public function saveUploadedFile($event)
    {
        $model = $this->owner;
        $arr_ids = [];
        if (in_array($model->scenario, $this->scenarios, true)) {
            $this->_file = UploadedFile::getInstances($model, $this->attribute);
            if (is_array($this->_file) && !empty($this->_file)) {
                $this->owner->{$this->savedAttribute} = implode(',', FileModel::fileSave($this->_file));
            } else {
                // Protect attribute
                unset($model->{$this->attribute});
            }
        }
    }
}
