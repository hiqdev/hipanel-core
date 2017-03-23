<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets\filePreview;

/**
 * Class InsetDimensions implements [[DimensionsInterface]] and provides
 * API to resize [[originalDimensions]] to [[targetDimensions]] using the following logic:.
 *
 * The smallest side (width or height) of the [[originalDimensions]] will be resized to
 * the [[targetDimensions]] side. The second side will be resized accordingly to the original
 * aspect ratio and be less than target side.
 *
 * Example:
 * ```php
 * $size = new InsetDimensions(
 *     new Dimensions(1000, 500),
 *     new Dimensions(300, 300)
 * );
 *
 * $width = $size->getWidth(); // 300 (maximum width to fit in target dimensions)
 * $height = $size->getHeight(); // 150 (height according to the original aspect ratio)
 * ```
 */
class InsetDimensions implements DimensionsInterface
{
    /**
     * @var Dimensions
     */
    private $originalDimensions;
    /**
     * @var Dimensions
     */
    private $targetDimensions;

    /**
     * InsetDimensions constructor.
     *
     * @param Dimensions $originalDimensions the original image dimensions
     * @param Dimensions $targetDimensions the target image dimensions
     */
    public function __construct(Dimensions $originalDimensions, Dimensions $targetDimensions)
    {
        $this->originalDimensions = $originalDimensions;
        $this->targetDimensions = $targetDimensions;
    }

    /**
     * @return int
     */
    public function getWidth()
    {
        if ($this->originalDimensions->getWidth() < $this->targetDimensions->getWidth()) {
            return $this->originalDimensions->getWidth();
        }

        if ($this->isVertical()) {
            return intval(ceil($this->targetDimensions->getWidth() * $this->originalDimensions->getRatio()));
        }

        return $this->targetDimensions->getWidth();
    }

    /**
     * @return int
     */
    public function getHeight()
    {
        if ($this->originalDimensions->getHeight() < $this->targetDimensions->getHeight()) {
            return $this->originalDimensions->getHeight();
        }

        if (!$this->isVertical()) {
            return intval(ceil($this->targetDimensions->getHeight() / $this->originalDimensions->getRatio()));
        }

        return $this->targetDimensions->getHeight();
    }

    /**
     * @return bool whether image is in the portrait orientation
     */
    private function isVertical()
    {
        return $this->originalDimensions->getRatio() < 1;
    }

    /**
     * {@inheritdoc}
     */
    public function getRatio()
    {
        return $this->originalDimensions->getRatio();
    }
}
