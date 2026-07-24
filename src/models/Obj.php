<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\models;

use hipanel\base\ModelTrait;

class Obj extends \hipanel\base\Model
{
    use ModelTrait;

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name', 'class_name'], 'safe'],
        ];
    }

    public function getObjClass()
    {
        static $objClasses = [];
        if (empty($objClasses[$this->class_name])) {
            $objClasses[$this->class_name] = ObjClass::get($this->class_name);
        }

        return $objClasses[$this->class_name];
    }
}
