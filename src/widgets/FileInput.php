<?php

declare(strict_types=1);

namespace hipanel\widgets;

use hipanel\assets\FileInputAsset;
use yii\helpers\Html;
use yii\widgets\InputWidget;

class FileInput extends InputWidget
{
    public function run()
    {
        Html::removeCssClass($this->options, 'form-control');
        $input = $this->hasModel()
            ? Html::activeFileInput($this->model, $this->attribute, $this->options)
            : Html::fileInput($this->name, $this->value, $this->options);
        FileInputAsset::register($this->getView());
        $this->view->registerJs("$('#$this->id').fileInput();");

        return $this->render('FileInput', [
            'id' => $this->id,
            'hiddenInput' => $input,
        ]);
    }
}
