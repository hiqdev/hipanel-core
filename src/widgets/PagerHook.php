<?php

namespace hipanel\widgets;

use Yii;
use yii\helpers\Html;
use yii\widgets\LinkPager;

class PagerHook extends LinkPager implements HookInterface
{
    use HookTrait;

    public string $tag = 'div';

    public string $content;

    public function init()
    {
        $this->registerJsHook('pager');
    }

    public function run()
    {
        return Html::tag($this->tag, $this->content ?? $this->getLoader(), array_merge(['id' => $this->getId()], $this->options));
    }

    private function getLoader(): string
    {
        return Html::tag('span', null, ['class' => 'fa fa-spinner fa-pulse', 'style' => 'margin-right: .7rem;']) . Yii::t('hipanel', 'loading pager...');
    }
}