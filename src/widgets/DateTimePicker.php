<?php

declare(strict_types=1);

namespace hipanel\widgets;

use hipanel\assets\DateTimePickerAsset;
use Yii;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\View;
use yii\widgets\InputWidget;

/**
 *
 * @property-read array $defaultOptions
 */
class DateTimePicker extends InputWidget
{
    private const string NAME = 'data-hiqdev-datetimepicker';
    public array $clientOptions = [];
    public string $icon = 'glyphicon-time';

    public function init()
    {
        parent::init();
        Html::addCssClass($this->options, 'form-control');
        $this->options[self::NAME] = uniqid();
    }

    public function run(): string
    {
        $input = $this->hasModel()
            ? Html::activeTextInput($this->model, $this->attribute, $this->options)
            : Html::textInput($this->name, $this->value, $this->options);
        $icon = Html::tag('span',
            '',
            ['class' => "glyphicon $this->icon form-control-feedback", 'style' => 'line-height: 3rem; color: #ccc;']);
        $this->registerClientScript();

        return Html::tag(
            'div',
            strtr('{input}{icon}', ['{input}' => $input, '{icon}' => $icon]),
            ['class' => 'form-group has-feedback date']
        );
    }

    protected function getDefaultOptions(): array
    {
        return array_filter([
            'time_24hr' => true,
            'enableTime' => true,
            'allowInput' => true,
            'dateFormat' => 'Y-m-d H:i:S',
            'locale' => Yii::$app->language === 'ru' ? 'ru' : null,
        ], static fn($value): bool => $value !== null);
    }

    protected function registerFlatpickrAsset(): void
    {
        DateTimePickerAsset::register($this->view);
    }

    private function registerClientScript(): void
    {
        $this->registerFlatpickrAsset();

        $id = $this->options['id'] ?? $this->id;
        $options = array_merge($this->getDefaultOptions(), $this->clientOptions);
        $jsOptions = Json::encode($options);
        $this->addToGlobalOptions($jsOptions);

        $this->view->registerJs(<<<"JS"
            (() => {
                if (typeof flatpickr === "function") {
                  flatpickr("#$id", $jsOptions);
                } else {
                  setTimeout(() => {
                    if (typeof flatpickr === "function") {
                      flatpickr("#$id", $jsOptions);
                    }
                  }, 1500);
                }
            })();
JS
            ,
            View::POS_LOAD);
    }

    private function addToGlobalOptions(string $jsOptions): void
    {
        $this->getView()->registerJs(<<<"JS"
            if (!window.hiqdev_datetimepicker_options) {
                window.hiqdev_datetimepicker_options = {};
            }
            window.hiqdev_datetimepicker_options['{$this->options[self::NAME]}'] = $jsOptions;
JS
        );
    }
}
