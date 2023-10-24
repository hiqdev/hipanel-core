<?php

declare(strict_types=1);

namespace hipanel\widgets;

use hipanel\assets\Vue2CdnAsset;
use hipanel\models\TaggableInterface;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class TagsReadOnly extends Widget
{
    public TaggableInterface $model;

    public function run(): ?string
    {
        $tags = Html::tag('span', $this->renderTags());
        return <<<"HTML"
            <span id="$this->id">
                <span class="tags" style="line-height: 3">
                    $tags
                </span>
            </span>
HTML;
    }

    private function renderTags(): string
    {
        $output = [];
        foreach ($this->model->tags as $tag) {
            $output[] = Html::tag(
                'span',
                $tag,
                ['class' => 'label label-default', 'style' => 'font-size: x-small;']
            );
        }

        if (empty($output)) {
            $id = $this->id;
            $this->view->registerCss("#$id span.text-muted:after { content: ''; white-space: nowrap; }");

            return Html::tag('span', null, ['class' => 'text-muted', 'style' => 'font-size: smaller; line-height: 3']);
        }

        return implode(' ', $output);
    }
}
