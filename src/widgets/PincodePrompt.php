<?php

namespace hipanel\widgets;

use hipanel\assets\PincodePromptAsset;
use yii\base\Widget;

class PincodePrompt extends Widget
{
    public $loadingText;

    public function run()
    {
        PincodePromptAsset::register($this->view);

        return $this->renderModalView();
    }

    protected function renderModalView()
    {
        return $this->render('pincode-prompt', [
            'widget' => $this
        ]);
    }
}
