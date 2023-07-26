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

        $tagsWithEditButton = $this->renderTagsWithEditButton();
        $tagInput = TagsInput::widget(['model' => $this->model]);

        return <<<"HTML"
            <span id="$this->id">
                <span class="tags" style="line-height: 3">$tagsWithEditButton</span>
                <span class="tags" style="display: none;">$tagInput</span>
            </span>
HTML;
    }

    private function renderTagsWithEditButton(): string
    {
        $output = [];
        $output[] = Html::beginTag(
            'span',
            [
                'style' => [
                    'display' => 'flex',
                    'flex-direction' => 'row',
                    'justify-content' => 'space-between',
                ],
            ],
        );
        $output[] = Html::tag('span', $this->renderTags());
        $output[] = Html::button(
            '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>',
            ['class' => 'btn btn-link', 'alt' => Yii::t('hipanel', 'Assign tags')]
        );
        $output[] = Html::endTag('span');

        return implode(' ', $output);
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
            return Html::tag(
                'sapn',
                Yii::t('hipanel', 'Tags have not yet been assigned'),
                ['class' => 'text-muted', 'style' => 'font-size: smaller; line-height: 3']
            );
        }

        return implode(' ', $output);
    }

    private function registerJs(): void
    {
        $this->view->registerJs(<<<"JS"
            ;(() => {
              const container = $("#$this->id");
              container.on("click", ".btn-link", function(event) {
                event.preventDefault();
                $(".tags", container).toggle();
              });
            })();
JS
        );
    }
}
