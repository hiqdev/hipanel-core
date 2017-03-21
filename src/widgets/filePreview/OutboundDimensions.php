<?php


namespace hipanel\widgets\filePreview;

/**
 * Class OutboundDimensions implements [[DimensionsInterface]] and provides
 * API to resize [[originalDimensions]] to [[targetDimensions]] using the following logic:
 *
 * The smallest side (width or height) of the [[originalDimensions]] will be resized to
 * the [[targetDimensions]] side. The second side will be resized accordingly to the original
 * aspect ratio.
 *
 * Example:
 * ```php
 * $size = new OutboundDimensions(
 *     new Dimensions(500, 1000),
 *     new Dimensions(300, 300)
 * );
 *
 * $width = $size->getWidth(); // 300 (matches target width)
 * $height = $size->getHeight(); // 600 (resized to keep aspect ratio)
 * ```
 */
class OutboundDimensions implements DimensionsInterface
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
     * OutboundDimensions constructor
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
            return $this->targetDimensions->getWidth();
        }

        return $this->targetDimensions->getWidth();
    }

    /**
     * @return float|int
     */
    public function getHeight()
    {
        if ($this->originalDimensions->getHeight() < $this->targetDimensions->getHeight()) {
            return $this->originalDimensions->getHeight();
        }

        if ($this->isVertical()) {
            return intval(ceil($this->targetDimensions->getHeight() / $this->originalDimensions->getRatio()));
        }

        return intval(ceil($this->targetDimensions->getHeight() / $this->originalDimensions->getRatio()));
    }

    /**
     * @return bool whether image is in the portrait orientation
     */
    private function isVertical()
    {
        return $this->originalDimensions->getRatio() < 1;
    }

    /**
     * @inheritdoc
     */
    public function getRatio()
    {
        return $this->originalDimensions->getRatio();
    }
}
