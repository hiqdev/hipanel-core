<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use hipanel\models\Ref;
use hiqdev\hiart\ActiveRecord;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

/**
 * Class RefFilter widget
 *
 * Usage:
 * Label::widget([
 *      'attribute' => 'state',
 *      'model'     => $searchModel,
 *      'gtype'     => 'state,domain',
 * ]);
 */
class RefFilter extends Widget
{
    /**
     * @var ActiveRecord
     */
    public $model;

    /**
     * @var string
     */
    public $gtype;

    /**
     * @var string
     */
    public $attribute;

    /**
     * @var array
     */
    public $options = [];

    static public function widget ($config = []) {
        if (isset($config['options'])) {
            $options = &$config['options'];
        }
        $vars = get_class_vars(get_class());
        foreach ($config as $k => $v) {
            if (array_key_exists($k,$vars)) continue;
            $options[$k] = $v;
            unset($config[$k]);
        };
        return parent::widget($config);
    }

    public function run () {
        parent::run();
        return $this->renderInput();
    }

    protected function renderInput () {
        print Html::activeDropDownList($this->model, $this->attribute, Ref::getList($this->gtype), ArrayHelper::merge([
            'class'     => 'form-control',
            'prompt'    => \Yii::t('app', '---'),
        ], $this->options));
    }

}
