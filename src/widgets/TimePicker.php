<?php

declare(strict_types=1);

namespace hipanel\widgets;

class TimePicker extends DateTimePicker
{
    public function init(): void
    {
        parent::init();

        $this->clientOptions = array_filter(array_merge([
            'enableTime' => true,
            'noCalendar' => true,
            'dateFormat' => 'H:i',
            'defaultDate' => '00:00',
        ], $this->clientOptions), static fn($value): bool => $value !== null);
    }
}
