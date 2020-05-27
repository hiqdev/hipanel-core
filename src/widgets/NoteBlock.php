<?php


namespace hipanel\widgets;


use hipanel\base\Model;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;

/**
 * Class NoteBlock
 * @package hipanel\widgets
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
        $res = '</br>';
        $res .= Html::tag('span', $this->model->getAttributeLabel($this->note) . ': ', ['class' => 'bold']);
        if (empty($this->noteOptions['url'])) {
            $value = $this->model->{$this->note};
            $res .= empty($value) ? $this->getEmptyMessage() : $value;
        } else {
            $res .= XEditable::widget([
                'model' => $this->model,
                'attribute' => $this->note,
                'pluginOptions' => $this->noteOptions,
            ]);
        }
        return $res;
    }

    private function getEmptyMessage()
    {
        return Html::tag('span', Yii::t('hipanel', 'Empty'), ['style' => ['font-style' => 'italic']]);
    }
}
