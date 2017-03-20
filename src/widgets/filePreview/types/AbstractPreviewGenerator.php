<?php


namespace hipanel\widgets\filePreview\types;


use hipanel\widgets\filePreview\Dimensions;
use hipanel\widgets\filePreview\DimensionsInterface;

abstract class AbstractPreviewGenerator
{
    protected $path;

    public function __construct($path)
    {
        $this->path = $path;
    }

    abstract public function asBytes(DimensionsInterface $dimensions);

    abstract public function getContentType();

    abstract public function getWidth();

    abstract public function getHeight();

    public function getDimensions()
    {
        return new Dimensions($this->getWidth(), $this->getHeight());
    }
}