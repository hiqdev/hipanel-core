<?php

declare(strict_types=1);

namespace hipanel\widgets;

use hipanel\base\Model;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class NoteBlock
 * @package hipanel\widgets
 *
 * @property-read string $emptyMessage
 */
class NoteBlock extends Widget
{
    /**
     * @var Model
     */
    public Model $model;

    /**
     * @var string
     */
    public string $note;

    /**
     * @var array
     */
    public array $noteOptions;

    /**
     * @inheritDoc
     */
    public function run()
    {
        if (empty($this->noteOptions['url'])) {
            $value = $this->model->{$this->note};
            $result = empty($value) ? $this->getEmptyMessage() : $value;
        } else {
            $result = XEditable::widget([
                'model' => $this->model,
                'attribute' => $this->note,
                'pluginOptions' => array_merge([
                    'emptytext' => $this->model->getAttributeLabel($this->note),
                    'type' => 'textarea',
                ], $this->noteOptions),
            ]);
        }

        return Html::tag('span', $result);
    }

    private function getEmptyMessage(): string
    {
        return Html::tag('span', Yii::t('hipanel', 'Empty'), ['style' => ['font-style' => 'italic']]);
    }
}
