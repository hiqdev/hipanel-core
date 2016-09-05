<?php

namespace hipanel\inputs;

class BooleanInput
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
