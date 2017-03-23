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
 * Interface DimensionsInterface represents dimensions values.
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
