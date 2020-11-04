<?php

namespace hipanel\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\web\View;

class SummaryHook extends Widget
{
    public string $tag = 'div';

    public array $options = [];

    public string $content;

    public function init()
    {
        parent::init();
        $this->registerJs();
    }

    public function run()
    {
        return Html::tag($this->tag, $this->content ?? $this->getLoader(), array_merge(['id' => $this->getId(), 'class' => 'summary'], $this->options));
    }

    private function registerJs(): void
    {
        $id = $this->getId();
        $this->view->registerJs("$.get(document.URL, summary => { $('#{$id}').html(summary) });", View::POS_LOAD);
    }

    private function getLoader(): string
    {
        return Html::tag('span', null, ['class' => 'fa fa-spinner fa-pulse', 'style' => 'margin-right: .7rem;']) . Yii::t('hipanel', 'loading summary...');
    }
}
