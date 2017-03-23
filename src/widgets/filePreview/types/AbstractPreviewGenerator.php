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

use hipanel\widgets\filePreview\Dimensions;
use hipanel\widgets\filePreview\DimensionsInterface;

abstract class AbstractPreviewGenerator implements PreviewGeneratorInterface
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function asBytes(DimensionsInterface $dimensions);

    /**
     * {@inheritdoc}
     */
    abstract public function getContentType();

    /**
     * {@inheritdoc}
     */
    abstract public function getWidth();

    /**
     * {@inheritdoc}
     */
    abstract public function getHeight();

    /**
     * {@inheritdoc}
     */
    public function getDimensions()
    {
        return new Dimensions($this->getWidth(), $this->getHeight());
    }
}
