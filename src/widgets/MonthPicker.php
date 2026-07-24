<?php

declare(strict_types=1);

namespace hipanel\widgets;

use hipanel\assets\DateTimePickerAsset;
use yii\helpers\Json;
use yii\web\JsExpression;

class MonthPicker extends DatePicker
{
    public function init(): void
    {
        parent::init();

        $this->clientOptions = array_filter(array_merge([
            'enableTime' => false,
            'dateFormat' => 'Y-m-01',
        ], $this->clientOptions), static fn($value): bool => $value !== null);
    }

    protected function registerFlatpickrAsset(): void
    {
        parent::registerFlatpickrAsset();
        DateTimePickerAsset::register($this->view)->js[] = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js';
        DateTimePickerAsset::register($this->view)->css[] = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css';
        $dateFormat = Json::encode($this->clientOptions['dateFormat']);
        $this->clientOptions['plugins'] = [new JsExpression("new monthSelectPlugin({ shorthand: true, dateFormat: $dateFormat })")];
    }
}
