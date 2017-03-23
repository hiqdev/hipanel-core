<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets\filePreview\types;

use hipanel\widgets\filePreview\DimensionsInterface;
use Imagine\Gd\Imagine;
use yii\imagine\Image;

class ImagePreviewGenerator extends AbstractPreviewGenerator
{
    /**
     * @var Imagine|\Imagine\Image\ImageInterface
     */
    private $imagine;

    /**
     * @return Imagine|\Imagine\Image\ImageInterface
     */
    protected function getImagine()
    {
        if ($this->imagine === null) {
            $this->imagine = Image::getImagine()->open($this->path);
        }

        return $this->imagine;
    }

    /**
     * {@inheritdoc}
     */
    public function asBytes(DimensionsInterface $dimensions)
    {
        return Image::thumbnail($this->getImagine(), $dimensions->getWidth(), $dimensions->getHeight());
    }

    /**
     * {@inheritdoc}
     */
    public function getContentType()
    {
        return mime_content_type($this->path);
    }

    /**
     * {@inheritdoc}
     */
    public function getWidth()
    {
        return $this->getImagine()->getSize()->getWidth();
    }

    /**
     * {@inheritdoc}
     */
    public function getHeight()
    {
        return $this->getImagine()->getSize()->getHeight();
    }
}
