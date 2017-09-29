<?php
/**
 * Hosting Plugin for HiPanel
 *
 * @link      https://github.com/hiqdev/hipanel-module-hosting
 * @package   hipanel-module-hosting
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2015-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets\obj;

use hipanel\models\Obj;
use Yii;
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
    
        return $this->getLabel() . '&nbsp;' .
            Html::a($this->model->name, ["@$alias/view", 'id' => $this->model->id], ['data-pjax' => 0]);
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
