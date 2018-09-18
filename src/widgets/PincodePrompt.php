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
use hipanel\modules\client\helpers\HasPINCode;
use Yii;
use yii\base\Widget;

class PincodePrompt extends Widget
{
    public $loadingText;
    /**
     * @var bool
     */
    private $hasPINCode;

    public function __construct(HasPINCode $hasPINCode, $config = [])
    {
        parent::__construct($config);
        $this->hasPINCode = $hasPINCode;
    }

    public function run()
    {
        PincodePromptAsset::register($this->view);

        return $this->render('pincode-prompt');
    }

    /**
     * @return bool
     */
    public function isPINFailed(): bool
    {
        return !$this->hasPINCode->__invoke() && Yii::$app->user->can('manage');
    }
}
