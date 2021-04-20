<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets\obj;

use hipanel\models\Obj;
use yii\helpers\Html;

class ObjLinkWidget extends \yii\base\Widget
{
    /**
     * @var Obj
     */
    public $model;

    protected $_label;

    public function run()
    {
        return $this->renderLink();
    }

    protected function renderLink()
    {
        $alias = $this->model->getObjClass()->getAlias();

        try {
            return Html::a($this->getLabel(), ["@$alias/view", 'id' => $this->model->id], ['data-pjax' => 0]);
        } catch (\Throwable $e) {
            return $this->model->id;
        }
    }

    public function setLabel($label)
    {
        $this->_label = $label;
    }

    public function getLabel()
    {
        if ($this->_label === null) {
            $this->_label = ObjLabelWidget::widget(['model' => $this->model]);
        }

        return $this->_label;
    }
}
