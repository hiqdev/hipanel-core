<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use Closure;
use hipanel\helpers\ArrayHelper;
use hipanel\widgets\XEditable;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class MainColumn extends DataColumn
{
    /**
     * @var true|string
     * true - note editing is enabled in this column, target attribute name is `note`
     * string - target atttribute name
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
    public function buildUrl($url)
    {
        if (strncmp($url, '/', 0) === 0) {
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
        if ($this->value !== null) {
            if (is_string($this->value)) {
                $value = ArrayHelper::getValue($model, $this->value);
            } else {
                $value = call_user_func($this->value, $model, $key, $index, $this);
            }
        } else {
            $value = $this->renderViewLink($model, $key, $index);
        }
        $note = $this->renderNoteLink($model, $key, $index);
        $extra = $this->renderExtra($model);
        $badges = $this->badges instanceof Closure ? call_user_func($this->badges, $model, $key, $index) : $this->badges;

        return $value . $extra . ($badges ? ' ' . $badges : '') . ($note ? '<br>' . $note : '');
    }

    public function renderExtra($model)
    {
        $value = $this->extraAttribute ? $model->{$this->extraAttribute} : null;

        return $value ? "<br>$value" : '';
    }

    public function renderViewLink($model, $key, $index)
    {
        $value = parent::renderDataCellContent($model, $key, $index);
        return Html::a($value, [$this->buildUrl('view'), 'id' => $model->id], ['class' => 'bold']);
    }

    /**
     * Renders link to edit note.
     * @param $model
     * @param $key
     * @param $index
     * @return string|null
     */
    public function renderNoteLink($model, $key, $index)
    {
        return $this->note ? Html::tag('span', Yii::t('hipanel', 'Note') . ': ', ['class' => 'bold']) . XEditable::widget([
                'model' => $model,
                'attribute' => $this->note === true ? 'note' : $this->note,
                'pluginOptions' => $this->noteOptions,
            ]) : null;
    }
}
