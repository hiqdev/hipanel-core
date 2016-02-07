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

namespace common\behaviors;

use common\models\File as FileModel;
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
     * @param \yii\base\ModelEven|boolean $event
     */
    public function saveUploadedFile($event)
    {
        $model = $this->owner;
        $arr_ids = [];
        if (in_array($model->scenario, $this->scenarios, true)) {
            $this->_file = UploadedFile::getInstances($model, $this->attribute);
            if (is_array($this->_file) && !empty($this->_file)) {
                $this->owner->{$this->savedAttribute} = implode(',', FileModel::fileSave($this->_file));
//                foreach ($this->_file as $file) {
//                    if ($file instanceof UploadedFile) {
//                        // Move to temporary destination
//                        $tempDestination = FileModel::getTempFolder() . DIRECTORY_SEPARATOR . uniqid() . '.' . $file->extension;
//                        FileHelper::createDirectory(dirname($tempDestination));
//                        $file->saveAs($tempDestination);
//                        // Prepare to final destination
//                        $url = FileModel::getTmpUrl(basename($tempDestination));
//                        $response =  FileModel::perform('Put', [
//                            'url' => $url,
//                            'filename' => basename($tempDestination)
//                        ]);
//
//                        $file_id = $arr_ids[] = $response['id'];
//                        $finalDestination = $this->getPath($file_id) . DIRECTORY_SEPARATOR . $file_id;
//                        FileHelper::createDirectory(dirname($finalDestination));
//                        if (!rename($tempDestination, $finalDestination))
//                            throw new \LogicException('rename function is not work');
//                        if (is_file($tempDestination))
//                            unlink($tempDestination);
//                    }
//                }
//                $this->owner->{$this->savedAttribute} = implode(',', $arr_ids);
            } else {
                // Protect attribute
                unset($model->{$this->attribute});
            }
        }
    }
}
