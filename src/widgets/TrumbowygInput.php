<?php declare(strict_types=1);

namespace hipanel\widgets;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\InputWidget;

class TrumbowygInput extends InputWidget
{
    private array $clientOptions = [
        'autogrow' => true,
    ];

    public function run(): void
    {
        if ($this->hasModel()) {
            echo Html::activeTextarea($this->model, $this->attribute, $this->options);
        } else {
            echo Html::textarea($this->name, $this->value, $this->options);
        }
        $this->registerClientScript();
    }

    private function registerClientScript(): void
    {
        $js = [];

        $id = $this->options['id'];


        $selector = "#$id";

        $options = Json::encode($this->clientOptions);
        $js[] = "$('$selector').trumbowyg($options);";

        $this->view->registerJs(implode("\n", $js));
    }
}
