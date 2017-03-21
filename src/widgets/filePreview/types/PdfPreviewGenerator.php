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

    /**
     * @return Imagick
     */
    protected function getImagick()
    {
        if ($this->imagick === null) {
            $this->imagick = new Imagick(realpath($this->path) . '[0]');
        }

        return $this->imagick;
    }

    /**
     * @inheritdoc
     */
    public function asBytes(DimensionsInterface $dimensions)
    {
        $im = clone $this->getImagick();
        $im->resizeImage($dimensions->getWidth(), $dimensions->getHeight(), Imagick::FILTER_LANCZOS, 1);

        return $im->getImageBlob();
    }

    /**
     * @inheritdoc
     */
    public function getContentType()
    {
        return 'image/jpeg';
    }

    /**
     * @inheritdoc
     */
    public function getWidth()
    {
        return $this->getImagick()->getImageWidth();
    }

    /**
     * @inheritdoc
     */
    public function getHeight()
    {
        return $this->getImagick()->getImageHeight();
    }
}
