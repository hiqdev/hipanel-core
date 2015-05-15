<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\base;

use Yii;

class View extends \yii\web\View
{
    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->initTheme();
    }

    /**
     * @throws \yii\base\InvalidConfigException
     */
    public function initTheme() {
        $theme = Yii::$app->session->get('user.theme', Yii::$app->params['skin']['default-theme']);
        $this->theme = Yii::createObject([
            'class' => '\yii\base\Theme',
            'pathMap' => ['@app/views' =>'@app/themes/'.$theme],
            'baseUrl' => '@web/themes/'.$theme,
        ]);
    }

    /**
     * Magic setter.
     *
     * @param int|string   $name
     * @param mixed        $value the element value
     */
    public function __set($name, $value)
    {
        if ($name && $this->canSetProperty($name)) {
            parent::__set($name, $value);
        } else {
            $this->params[$name] = $value;
        }
    }

    /**
     * Magic getter.
     *
     * @param string $name component or property name
     *
     * @return mixed param or property value
     */
    public function __get($name)
    {
        if ($name && $this->canGetProperty($name)) {
            return parent::__get($name);
        } else {
            return $this->params[$name];
        }
    }

    /**
     * Magic is set checker.
     *
     * @param string $name the property or param name to check.
     *
     * @return bool whether the value is set.
     */
    public function __isset($name)
    {
        return ($name && parent::__isset($name)) || isset($this->params[$name]);
    }

    /**
     * Magic unsetter.
     *
     * @param string $name the property or param name to unset.
     */
    public function __unset($name)
    {
        if ($name && $this->canSetProperty($name)) {
            parent::__unset($name);
        } else {
            unset($this->params[$name]);
        }
    }

}
