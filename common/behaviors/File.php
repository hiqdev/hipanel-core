<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 11.02.15
 * Time: 17:59
 */
namespace common\behaviors;

use yii;
use yii\base\Behavior;
use yii\db\BaseActiveRecord;
use yii\web\UploadedFile;

class File extends Behavior
{
    /**
     * @var string the directory to store uploaded files. You may use path alias here.
     * If not set, it will use the "upload" subdirectory under the application runtime path.
     */
    public $path = '@runtime/upload';

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
     * @throws InvalidConfigException
     */
    public function init() {
        parent::init();
        $this->path = Yii::getAlias($this->path);
    }

    /**
     * @return array
     */
    public function events() {
        return [
            BaseActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            BaseActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
        ];
    }

    /**
     * Event handler for beforeSave
     * @param \yii\base\ModelEvent $event
     */
    public function beforeSave($event) {
        die('123123123');
        $model = $this->owner;
        if (in_array($model->scenario, $this->scenarios)) {
            if ($this->_file instanceof UploadedFile) {
                if (!$model->getIsNewRecord() && $model->isAttributeChanged($this->attribute)) {
                    if ($this->unlinkOnSave === true) {
                        $this->delete($this->attribute, true);
                    }
                }
                $model->setAttribute($this->attribute, $this->_file->name);
            }
            else {
                // Protect attribute
                unset($model->{$this->attribute});
                // $model->setAttribute($this->attribute, $model->getOldAttribute($this->attribute));
            }
        }
        else {
//            if (!$model->getIsNewRecord() && $model->isAttributeChanged($this->attribute)) {
//                if ($this->unlinkOnSave === true) {
//                    $this->delete($this->attribute, true);
//                }
//            }
        }
    }
}