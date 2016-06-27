<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

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
     * @var array will be passed to the ```pluginOptions``` of [[XEditable]] plugin
     */
    public $noteOptions = [];

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
        $value = parent::renderDataCellContent($model, $key, $index);
        $html  = Html::a($value, [$this->buildUrl('view'), 'id' => $model->id], ['class' => 'bold']);
        if ($this->note) {
            $html .= $this->renderEditableNote($model, $key, $index);
        }
        return $html;
    }

    /**
     * Renders link to edit note.
     * @param $model
     * @param $key
     * @param $index
     * @return string
     */
    public function renderEditableNote($model, $key, $index)
    {
        return '<br>' . Html::tag('span', Yii::t('app', 'Note') . ': ', ['class' => 'bold']) . XEditable::widget([
            'model'         => $model,
            'attribute'     => $this->note === true ? 'note' : $this->note,
            'pluginOptions' => $this->noteOptions,
        ]);
    }
}
