<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use hipanel\models\Ref;
use hiqdev\hiart\ActiveRecord;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class RefFilter widget.
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
     * @var array additional find options that will be passed to [[Ref]] model
     */
    public $findOptions = [];

    /**
     * @var string
     */
    public $attribute;

    /**
     * @var string Dictionary name for i18n module to translate refs
     */
    public $i18nDictionary;

    /**
     * @var array of elements that will be merged with elements extracted from [[Ref]]
     * and passed to [[ArrayHelper::merge()]] as a second attribute.
     *
     * @see ArrayHelper::merge()
     */
    public $elementOverrides = [];

    /**
     * @var array
     */
    public $options = [];

    public static function widget($config = [])
    {
        if (isset($config['options'])) {
            $options = &$config['options'];
        }
        $vars = get_class_vars(get_class());
        foreach ($config as $k => $v) {
            if (array_key_exists($k, $vars)) {
                continue;
            }
            $options[$k] = $v;
            unset($config[$k]);
        }
        return parent::widget($config);
    }

    public function run()
    {
        parent::run();
        return $this->renderInput();
    }

    protected function renderInput()
    {
        return Html::activeDropDownList($this->model, $this->attribute, $this->getRefs(), ArrayHelper::merge([
            'class'     => 'form-control',
            'prompt'    => Yii::t('hipanel', '----------'),
        ], $this->options));
    }

    protected function getRefs()
    {
        $elements = Ref::getList($this->gtype, $this->i18nDictionary, $this->findOptions);

        return ArrayHelper::merge($elements, $this->elementOverrides);
    }
}
