<?php

namespace hipanel\inputs;

class OptionsInput
{
    private $options;

    public function __construct($options)
    {
        $this->options = $options;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
