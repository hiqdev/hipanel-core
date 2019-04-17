<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use hipanel\logic\Impersonator;
use yii\base\Widget;
use yii\helpers\Html;

class ImpersonationStatus extends Widget
{
    /**
     * @var Impersonator
     */
    private $impersonator;

    public function __construct(Impersonator $impersonator, $config = [])
    {
        $this->impersonator = $impersonator;
        parent::__construct($config);
    }

    public function run()
    {
        if (!$this->impersonator->isUserImpersonated()) {
            return '';
        }

        return Html::a(
            Html::tag('i', '', ['class' => 'fa fa-user-secret']) . ' ' . \Yii::t('hipanel', 'Unimpersonate'),
            ['/site/unimpersonate']
        );
    }
}
