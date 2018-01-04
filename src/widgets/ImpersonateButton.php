<?php

namespace hipanel\widgets;

use hipanel\logic\Impersonator;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class ImpersonateButton extends Widget
{
    /**
     * @var Impersonator
     */
    private $impersonator;

    /**
     * @var \hipanel\modules\client\models\Client
     */
    public $model;

    public function __construct(Impersonator $impersonator, $config = [])
    {
        $this->impersonator = $impersonator;
        parent::__construct($config);
    }

    public function run()
    {
        return Html::a(
            Html::tag('i', '', ['class' => 'fa fa-user-secret fa-flip-horizontal fa-fw']) . Yii::t('hipanel', 'Impersonate'),
            ['/site/impersonate', 'user_id' => $this->model->id]
        );
    }
}
