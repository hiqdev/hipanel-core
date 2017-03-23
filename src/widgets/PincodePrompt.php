<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

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
            'widget' => $this,
        ]);
    }
}
