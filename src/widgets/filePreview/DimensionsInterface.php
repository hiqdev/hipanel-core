<?php


namespace hipanel\widgets\filePreview;


interface DimensionsInterface
{
    const ORIENTATION_HORIZONTAL = 0;

    const ORIENTATION_VERTICAL = 1;

    public function getWidth();

    public function getHeight();

    public function getRatio();
}