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
use hipanel\widgets\Label;

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
