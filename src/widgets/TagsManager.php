<?php

declare(strict_types=1);

namespace hipanel\widgets;

use hipanel\assets\Vue2CdnAsset;
use hipanel\models\TaggableInterface;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

class TagsManager extends Widget
{
    public TaggableInterface $model;

    public function run(): ?string
    {
        Vue2CdnAsset::register($this->view);
        $this->registerJs();
        $this->view->registerCss(".editable-click { text-decoration: none; border-bottom: dashed 1px #0088cc; }");

        $tagsWithEditButton = $this->renderTagsWithLabel();
        $tagInput = $this->model->isTagsReadOnly() ? '' : TagsInput::widget(['model' => $this->model]);

        return <<<"HTML"
            <span id="$this->id">
                <span class="tags">$tagsWithEditButton</span>
                <span class="tags" style="display: none;">$tagInput</span>
            </span>
HTML;
    }

    private function renderTagsWithLabel(): string
    {
        $output[] = Html::beginTag('span');
        $output[] = Html::tag('span', Yii::t('hipanel', 'Tags:'), ['class' => 'text-bold']);
        $output[] = Html::tag('span', $this->renderTags());
        $output[] = Html::endTag('span');

        return implode(' ', $output);
    }

    private function renderTags(): string
    {
        $output = [];
        foreach ($this->model->tags as $tag) {
            $output[] = Html::tag('span', $tag);
        }

        if (empty($output)) {
            $id = $this->id;
            $content = Yii::t('hipanel', 'Tags have not yet been assigned');
            $this->view->registerCss("#$id span.text-muted:after { content: '$content'; white-space: nowrap; }");

            $output[] = Html::tag('span', null, ['class' => 'text-muted', 'style' => 'font-size: smaller;']);
        }

        return Html::tag('span', implode(', ', $output), ['class' => $this->model->isTagsReadOnly() ? '' : 'editable-click clickable']);
    }

    private function registerJs(): void
    {
        $this->view->registerJs(<<<"JS"
            ;(() => {
              const container = $("#$this->id");
              container.on("click", ".editable-click", function(event) {
                event.preventDefault();
                $(".tags", container).toggle();
              });
            })();
JS
        );
    }
}
