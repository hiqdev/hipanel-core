<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\helpers;

use Yii;
use yii\helpers\Html;

class FontIcon extends \yii\base\BaseObject
{
    public $tag;

    public $content;

    public $options = [];

    public static function i($name, $params = [])
    {
        return self::build('i', $name, $params);
    }

    public static function build($tag, $name, $params = [])
    {
        $class = get_called_class();

        return Yii::createObject(compact('class', 'tag', 'name', 'params'));
    }

    public function __toString()
    {
        return $this->render();
    }

    public function addClass($class)
    {
        Html::addCssClass($this->options, $class);

        return $this;
    }

    public function setName($name)
    {
        $this->addClass(self::nameClass($name));
    }

    public function setRotate($angle)
    {
        $this->rotate($angle);
    }

    public function rotate($angle)
    {
        return $this->addClass("fa-rotate-$angle");
    }

    public function border()
    {
        return $this->addClass('fa-border');
    }

    public function fixedWidth()
    {
        return $this->addClass('fa-fw');
    }

    public static function nameClass($name)
    {
        return (str_starts_with((string)$name, 'fa-') ? 'fa ' : '') . $name;
    }

    public function setParams($params)
    {
        foreach ($params as $k => $v) {
            $this->{$k} = $v;
        }
    }

    public function render()
    {
        return Html::tag($this->tag, $this->content, $this->options);
    }
}
