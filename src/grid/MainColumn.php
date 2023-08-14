<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use Closure;
use hipanel\helpers\ArrayHelper;
use hipanel\models\TaggableInterface;
use hipanel\widgets\NoteBlock;
use hipanel\widgets\TagsManager;
use yii\helpers\Html;
use yii\helpers\Url;

class MainColumn extends DataColumn
{
    /**
     * @var true|string|array
     * true - note editing is enabled in this column, target attribute name is `note`
     * string - target atttribute name
     * array - array of notes for an attribute
     */
    public $note;

    /**
     * @var string name of attribute with extra data showed under main data
     */
    public $extraAttribute;

    /**
     * @var array will be passed to the ```pluginOptions``` of [[XEditable]] plugin
     */
    public $noteOptions = [];

    /**
     * @var string|Closure badges string or callback to render badges
     */
    public $badges;

    /**
     * Builds url.
     * @param string $url
     * @return string
     */
    protected function buildUrl($url)
    {
        if (strncmp($url, '/', 1) === 0) {
            return $url;
        }
        $baseUrl = isset($this->grid->controllerUrl) ? $this->grid->controllerUrl : '';

        return $baseUrl ? Url::to($baseUrl . '/' . $url) : Url::to($url);
    }

    public function init()
    {
        parent::init();
        $this->noteOptions = ArrayHelper::merge([
            'url' => $this->buildUrl('set-note'),
        ], $this->noteOptions);
    }

    /** {@inheritdoc} */
    protected function renderDataCellContent($model, $key, $index)
    {
        $value = $this->renderValue($model, $key, $index);
        $note = $this->renderNoteLink($model, $key, $index);
        $extra = $this->renderExtra($model);
        $badges = $this->renderBadges($model, $key, $index);
        $tags = $this->renderTags($model);

        return $value . $extra . $badges . $note . $tags;
    }

    protected function renderTags($model): string
    {
        if (!$model instanceof TaggableInterface) {
            return '';
        }
        if ($model->isTagsHidden()) {
            return '';
        }
        $output = [];
        $output[] = '<br>';
        $output[] = TagsManager::widget(['model' => $model]);

        return implode(' ', $output);
    }

    protected function renderValue($model, $key, $index)
    {
        if ($this->value !== null) {
            if (is_string($this->value)) {
                $value = ArrayHelper::getValue($model, $this->value);
            } else {
                $value = call_user_func($this->value, $model, $key, $index, $this);
            }
        } else {
            $value = $this->renderViewLink($model, $key, $index);
        }
        return $value;
    }

    protected function renderBadges($model, $key, $index)
    {
        $badges = $this->badges instanceof Closure
                    ? call_user_func($this->badges, $model, $key, $index)
                    : $this->badges;

        return $badges ? (' ' . $badges) : '';
    }

    protected function renderExtra($model)
    {
        $value = $this->extraAttribute ? $model->{$this->extraAttribute} : null;

        return $value ? "<br>$value" : '';
    }

    protected function renderViewLink($model, $key, $index)
    {
        $value = parent::renderDataCellContent($model, $key, $index);

        return Html::a($value, [$this->buildUrl('view'), 'id' => $model->id], ['class' => 'bold']);
    }

    /**
     * Renders link to edit note.
     * @param $model
     * @param $key
     * @param $index
     * @return string
     */
    protected function renderNoteLink($model, $key, $index)
    {
        if (empty($this->note)) {
            return '';
        }
        if (is_array($this->note)) {
            return array_reduce(
                $this->note,
                fn (string $res, string $note): string => $res . NoteBlock::widget([
                    'model' => $model,
                    'note' => $note,
                    'noteOptions' => $this->noteOptions[$note],
                ]),
                '',
            );
        }
        if (is_string($this->note)) {
            return NoteBlock::widget([
                'model' => $model,
                'note' => $this->note,
                'noteOptions' => $this->noteOptions,
            ]);
        }
        return '';
    }
}
