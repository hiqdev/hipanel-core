<?php


namespace hipanel\widgets\filePreview;


class Dimensions implements DimensionsInterface
{
    protected $width;
    protected $height;

    public function __construct($width, $height)
    {
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    public function getRatio()
    {
        if ($this->getWidth() == 0 || $this->getHeight() == 0) {
            return 1;
        }

        return $this->getWidth() / $this->getHeight();
    }
}