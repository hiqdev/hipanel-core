<?php


namespace hipanel\widgets\filePreview;

/**
 * Interface DimensionsInterface represents dimensions values
 */
interface DimensionsInterface
{
    /**
     * @return integer
     */
    public function getWidth();

    /**
     * @return integer
     */
    public function getHeight();

    /**
     * The aspect ration of width to height.
     * Must calculated as `width/height`.
     *
     * @return float
     */
    public function getRatio();
}
