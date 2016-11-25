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

    /**
     * Apply js to view
     */
    protected function registerClientScript()
    {
        $this->getView()->registerJs(";$('button.{$this->selectorClass}').on('click', function () {window.history.go({$this->getBackStepsCount()});});");
    }

    /**
     * @return int
     */
    protected function getBackStepsCount()
    {
        return -Yii::$app->session[$this->getIdentifier()];
    }

    /**
     * @return string
     */
    protected function getIdentifier()
    {
        return self::class;
    }

    /**
     * @return string
     */
    protected function getCurrentUrl()
    {
        return $this->removeParams(Yii::$app->request->absoluteUrl);
    }

    /**
     * @return string
     */
    protected function getReferrerUrl()
    {
        return $this->removeParams(Yii::$app->request->referrer);
    }

    /**
     * Remove params from url
     * @return string
     */
    protected function removeParams($url)
    {
        return explode('?', $url, 2)[0];
    }
}
