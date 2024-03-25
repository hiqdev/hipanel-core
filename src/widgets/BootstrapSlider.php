<?php

declare(strict_types=1);

namespace hipanel\widgets;

use Closure;
use hipanel\assets\BootstrapSliderAsset;
use ReflectionClass;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

/**
 * @see https://github.com/seiyria/bootstrap-slider
 */
final class BootstrapSlider extends InputWidget
{
    use SliderAttributesTrait;

    public ?Closure $boundaryValues = null;
    private bool $disabled = true;

    public function init(): void
    {
        parent::init();
        if ($this->boundaryValues) {
            $boundaries = $this->boundaryValues->bindTo($this)();
            $this->min = (int)$boundaries['min'];
            $this->max = (int)$boundaries['max'];
        }
    }

    public function run(): string
    {
        $this->buildOptions();
        $input = $this->hasModel()
            ? Html::activeTextInput($this->model, $this->attribute, $this->options)
            : Html::textInput($this->name, $this->value, $this->options);

        $this->registerClientScript();

        return Html::tag('fieldset', $input, ['disabled' => $this->disabled]);
    }

    private function registerClientScript(): void
    {
        BootstrapSliderAsset::register($this->view);
        $options = !empty($this->clientOptions) ? Json::encode($this->clientOptions) : '';
        $id = $this->options['id'];
        $js[] = /** @lang JavaScript */
            "
          ;jQuery('#$id').slider($options).on('slideStop', function (event) {
            $(this).parent().prop('disabled', false);
            $(this).trigger('change');
          });
        ";
        $this->view->registerJs(implode("\n", $js));
    }

    private function buildValue(): string
    {
        $value = Html::getAttributeValue($this->model, $this->attribute);
        if ($value) {
            return Json::htmlEncode(array_map('intval', explode(',', $value)));
        }

        return Json::htmlEncode([$this->min, $this->max]);
    }

    private function buildOptions(): void
    {
        $reflect = new ReflectionClass(SliderAttributesTrait::class);
        foreach ($reflect->getProperties() as $property) {
            $this->options['data']["slider-$property->name"] = $this->{$property->name};
        }
        $this->options['data']['slider-value'] = $this->buildValue();
    }
}
