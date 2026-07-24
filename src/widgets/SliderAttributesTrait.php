<?php

declare(strict_types=1);

namespace hipanel\widgets;

trait SliderAttributesTrait
{
    public int|float $min = 0;
    public int|float $max = 0;
    public int|float $step = 1;
    public bool $range = true;
    public string $tooltip = 'show';
    public string $selection = 'before';
}
