<?php

namespace hipanel\models;

use Closure;
use Yii;

class ObjClass extends \yii\base\BaseObject
{
    public $knownClasses = [];

    protected $className;

    protected $_alias;

    protected $_color;

    protected $_label;

    public function __construct($className, $config = [])
    {
        parent::__construct($config);
        $this->className = $className;
    }

    public function getAlias()
    {
        if ($this->_alias === null) {
            $this->_alias = $this->getValue('alias');
        }
        if ($this->_alias === null) {
            $this->_alias = $this->className;
        }

        return $this->_alias;
    }

    public function getColor()
    {
        if ($this->_color === null) {
            $this->_color = $this->getValue('color');
        }

        return $this->_color;
    }

    public function getLabel()
    {
        if ($this->_label === null) {
            $this->_label = $this->findLabel();
        }

        return $this->_label;
    }

    public function findLabel()
    {
        $label = $this->getValue('label');
        if ($label instanceof Closure) {
            $label = call_user_func($label, $this);
        }
        if (!$label) {
            $label = ucfirst($this->className);
        }

        return $label;
    }

    public function getValue($key)
    {
        if (isset($this->knownClasses[$this->className][$key])) {
            return $this->knownClasses[$this->className][$key];
        }

        return null;
    }

    public static function get($className)
    {
        return Yii::$container->get(static::class, [$className]);
    }
}
