<?php


namespace hipanel\widgets\filePreview\types;

use hipanel\widgets\filePreview\DimensionsInterface;
use Imagine\Image\ManipulatorInterface;
use yii\imagine\Image;

class ImagePreviewGenerator extends AbstractPreviewGenerator
{
    private $imagine;

    protected function getImagine()
    {
        if ($this->imagine === null) {
            $this->imagine = Image::getImagine()->open($this->path);
        }

        return $this->imagine;
    }

    public function asBytes(DimensionsInterface $dimensions)
    {
        return Image::thumbnail($this->getImagine(), $dimensions->getWidth(), $dimensions->getHeight());
    }

    public function getContentType()
    {
        return mime_content_type($this->path);
    }

    public function getWidth()
    {
        return $this->getImagine()->getSize()->getWidth();
    }

    public function getHeight()
    {
        return $this->getImagine()->getSize()->getHeight();
    }
}