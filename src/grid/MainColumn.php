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
use Throwable;
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
        $baseUrl = $this->grid->controllerUrl ?? '';

        return $baseUrl ? Url::to($baseUrl . '/' . $url) : Url::to($url);
    }

    public function init()
    {
        parent::init();
        $this->noteOptions = ArrayHelper::merge(['url' => $this->buildUrl('set-note')], $this->noteOptions);
        $this->contentOptions = $this->contentOptions instanceof Closure ? call_user_func($this->contentOptions, $this->grid->filterModel) : $this->contentOptions;
        $this->contentOptions['style'] .= ' white-space: nowrap;';
    }

    /** {@inheritdoc} */
    protected function renderDataCellContent($model, $key, $index): string
    {
        $value = $this->renderValue($model, $key, $index);
        $extra = $this->renderExtra($model);
        $badges = $this->renderBadges($model, $key, $index);
        $noteAndLabel = $this->renderNoteLinks($model);
        $tags = $this->renderTags($model);

        return Html::tag(
            'span',
            implode(" ",
                array_map(static fn(string $html): string => Html::tag('span',
                    $html,
                    ['style' => 'display: flex; flex-direction: row; flex-wrap: nowrap; gap: 1rem;']), [
                    Html::tag('span', implode('', array_filter([$value, $extra, $badges]))),
                    $noteAndLabel,
                    $tags,
                ])),
            ['style' => 'display: flex; flex-direction: column; flex-wrap: nowrap;']
        );
    }

    protected function renderTags($model): string
    {
        if ($model instanceof TaggableInterface && !$model->isTagsHidden()) {
            return TagsManager::widget(['model' => $model]);
        }

        return '';
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

    protected function renderBadges($model, $key, $index): ?string
    {
        $badges = $this->badges instanceof Closure ? call_user_func($this->badges, $model, $key, $index) : $this->badges;

        return $badges ? (' ' . $badges) : null;
    }

    protected function renderExtra($model): ?string
    {
        return $this->extraAttribute ? $model->{$this->extraAttribute} : null;
    }

    protected function renderViewLink($model, $key, $index): string
    {
        $value = parent::renderDataCellContent($model, $key, $index);

        return Html::a($value, [$this->buildUrl('view'), 'id' => $model->id], ['class' => 'bold']);
    }

    /**
     * Renders link to edit note.
     * @param $model
     * @return string
     * @throws Throwable
     */
    protected function renderNoteLinks($model): string
    {
        if (empty($this->note)) {
            return '';
        }
        if (is_array($this->note)) {
            return array_reduce(
                $this->note,
                fn(string $res, string $note): string => $res . NoteBlock::widget([
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
