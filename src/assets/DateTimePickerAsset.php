<?php

declare(strict_types=1);

namespace hipanel\assets;

use Yii;
use yii\web\AssetBundle;

class DateTimePickerAsset extends AssetBundle
{
    public $css = ['https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css'];
    public $js = ['https://cdn.jsdelivr.net/npm/flatpickr'];

    public function init(): void
    {
        if (Yii::$app->language === 'ru') {
            $this->js[] = 'https://npmcdn.com/flatpickr/dist/l10n/ru.js';
        }
    }
}
