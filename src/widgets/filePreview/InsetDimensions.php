<?php


namespace hipanel\widgets\filePreview;


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

    public function __construct(Dimensions $originalDimensions, Dimensions $targetDimensions)
    {
        $this->originalDimensions = $originalDimensions;
        $this->targetDimensions = $targetDimensions;
    }

    public function getWidth()
    {
        if ($this->originalDimensions->getWidth() < $this->targetDimensions->getWidth()) {
            return $this->originalDimensions->getWidth();
        }

        if ($this->isVertical()) {
            return ceil($this->targetDimensions->getWidth() * $this->originalDimensions->getRatio());
        }

        return $this->targetDimensions->getWidth();
    }

    public function getHeight()
    {
        if ($this->originalDimensions->getHeight() < $this->targetDimensions->getHeight()) {
            return $this->originalDimensions->getHeight();
        }

        if (!$this->isVertical()) {
            return ceil($this->targetDimensions->getHeight() / $this->originalDimensions->getRatio());
        }

        return $this->targetDimensions->getHeight();
    }

    private function isVertical()
    {
        return $this->originalDimensions->getRatio() < 1;
    }

    public function getRatio()
    {
        return $this->originalDimensions->getRatio();
    }
}