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
use hipanel\widgets\Label;
use Yii;

class ObjLabelWidget extends Label
{
    /**
     * @var Obj
     */
    public $model;

    public function init()
    {
        $objClass = $this->model->getObjClass();
        $this->setColor($objClass->getColor());
        $this->setLabel($objClass->getLabel());
        parent::init();
    }
}
