<?php

declare(strict_types=1);

namespace hipanel\widgets;

class DatePicker extends DateTimePicker
{
    public string $icon = 'glyphicon-calendar';

    public function init(): void
    {
        parent::init();

        $this->clientOptions = array_filter(array_merge([
            'enableTime' => false,
            'dateFormat' => 'Y-m-d',
        ], $this->clientOptions), static fn($value): bool => $value !== null);
    }
}
