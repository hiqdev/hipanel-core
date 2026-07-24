<?php

namespace hipanel\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class SummaryHook extends Widget implements HookInterface
{
    use HookTrait;

    public string $tag = 'div';

    public array $options = [];

    public string $content;

    public function init()
    {
        parent::init();
        $this->registerJsHook('summary');
    }

    public function run()
    {
        return Html::tag($this->tag, $this->content ?? $this->getLoader(), array_merge(['id' => $this->getId()], $this->options));
    }

    private function getLoader(): string
    {
        return Html::tag('span', null, ['class' => 'fa fa-spinner fa-pulse', 'style' => 'margin: .7rem; 0 1rem']) . Yii::t('hipanel', 'loading summary...');
    }
}
