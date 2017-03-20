<?php


namespace hipanel\widgets\filePreview\types;

use hipanel\widgets\filePreview\DimensionsInterface;
use Imagick;

class PdfPreviewGenerator extends AbstractPreviewGenerator
{
    /**
     * @var Imagick
     */
    private $imagick;

    protected function getImagick()
    {
        if ($this->imagick === null) {
            $this->imagick = new Imagick(realpath($this->path));
        }

        return $this->imagick;
    }

    public function asBytes(DimensionsInterface $dimensions)
    {
        $im = clone $this->getImagick();
        $im->resizeImage($dimensions->getWidth(), $dimensions->getHeight(), Imagick::FILTER_LANCZOS, 1);

        return $im->getImageBlob();
    }

    public function getContentType()
    {
        return 'image/jpeg';
    }

    public function getWidth()
    {
        return $this->getImagick()->getImageWidth();
    }

    public function getHeight()
    {
        return $this->getImagick()->getImageHeight();
    }
}