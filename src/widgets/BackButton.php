<?php

namespace hipanel\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class BackButton extends Widget
{
    /**
     * Html options
     * @var array
     */
    public $options = [
        'class' => 'btn btn-default',
    ];

    /**
     * @var string
     */
    public $selectorClass = 'btn-cancel';

    /**
     * @var string
     */
    public $label;

    public function run()
    {
        $curr = $this->getCurrentUrl();
        $ref = $this->getReferrerUrl();
        if ($curr === $ref) {
            Yii::$app->session[$this->getIdentifier()] = Yii::$app->session[$this->getIdentifier()] + 1;
        } else {
            Yii::$app->session[$this->getIdentifier()] = 1;
        }
        $this->registerClientScript();
        Html::addCssClass($this->options, $this->selectorClass);

        return Html::button($this->getLabel(), $this->options);
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label !== null ? $this->label : Yii::t('hipanel', 'Cancel');
    }

    protected function registerClientScript()
    {
        $this->getView()->registerJs(";$('.{$this->selectorClass}').on('click', function () {window.history.go({$this->getStep()});});");
    }

    /**
     * @return int
     */
    protected function getStep()
    {
        return -Yii::$app->session[$this->getIdentifier()];
    }

    protected function getIdentifier()
    {
        return self::class;
    }

    protected function getCurrentUrl()
    {
        return Yii::$app->request->absoluteUrl;
    }

    protected function getReferrerUrl()
    {
        $out = null;
        $referrer = Yii::$app->request->referrer;
        $position = strpos($referrer, '?');
        if ($position !== false) {
            $out = substr($referrer, 0, $position);
        } else {
            $out = $referrer;
        }

        return $out;
    }
}
