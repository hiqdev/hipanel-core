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
use hiqdev\xeditable\widgets\XEditable;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;

class MainColumn extends DataColumn
{
    /**
     * @var boolean|string whether note editing is enabled in this column
     */
    public $note;

    /**
     * @var array will be passed to the ```pluginOptions``` of [[XEditable]] plugin
     */
    public $noteOptions = [];

    public function init()
    {
        parent::init();
        $this->noteOptions = ArrayHelper::merge([
            'emptytext' => Yii::t('app', 'set note'),
            'url'       => Url::to('set-note'),
        ], $this->noteOptions);
    }

    /** {@inheritdoc} */
    protected function renderDataCellContent($model, $key, $index)
    {
        $value = parent::renderDataCellContent($model, $key, $index);
        $html  = Html::a($value, ['view', 'id' => $model->id], ['class' => 'bold']);
        if ($this->note) {
            $html .= $this->renderEditableNote($model, $key, $index);
        }
        return $html;
    }

    /**
     * Renders editable note field.
     *
     * @param $model
     * @param $key
     * @param $index
     * @throws \Exception
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
