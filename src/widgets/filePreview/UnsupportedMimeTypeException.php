<?php


namespace hipanel\widgets\filePreview;


use yii\base\Exception;

/**
 * Class UnsupportedMimeTypeException
 */
class UnsupportedMimeTypeException extends Exception
{
    /**
     * @inheritdoc
     */
    public function getName()
    {
        return 'Preview render of this file type is not supported';
    }
}
