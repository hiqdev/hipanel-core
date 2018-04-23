<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\behaviors;

use hipanel\base\Model;
use hipanel\components\FileStorage;
use Yii;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use yii\web\UploadedFile;

class File extends Behavior
{
    /**
     * @var string the attribute which holds the attachment
     */
    public $attribute = 'file';

    /**
     * @var array the scenarios in which the behavior will be triggered
     */
    public $scenarios = [];

    /**
     * @var string the attribute that will receive the file id
     */
    public $targetAttribute;

    /**
     * @return array
     */
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'processFiles',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'processFiles',
        ];
    }

    /**
     * Event handler for beforeInsert and beforeUpdate actions.
     *
     * @param \yii\base\ModelEvent $event
     */
    public function processFiles($event = null)
    {
        /** @var Model $model */
        $model = $this->owner;
        $ids = [];

        if (in_array($model->scenario, $this->scenarios, true)) {
            $files = UploadedFile::getInstances($model, $this->attribute);

            foreach ($files as $file) {
                $model = $this->uploadFile($file);
                $ids[] = $model->id;
            }

            if (!empty($ids)) {
                $this->owner->{$this->targetAttribute} = implode(',', $ids);
            } else {
                // Protect attribute
                $model->{$this->attribute} = null;
            }
        }
    }

    /**
     * Uploads file to the API server.
     *
     * @param UploadedFile $file
     * @return \hipanel\models\File
     */
    private function uploadFile(UploadedFile $file)
    {
        /** @var FileStorage $fileStorage */
        $fileStorage = Yii::$app->get('fileStorage');

        $filename = $fileStorage->saveUploadedFile($file);
        return $fileStorage->put($filename, $file->name);
    }
}
