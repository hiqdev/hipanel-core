<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use hipanel\helpers\ArrayHelper;
use Yii;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\InputWidget;

class PasswordInput extends InputWidget
{
    /**
     * @var bool Whether to show 'random generator'
     */
    public $randomGenerator = true;

    /**
     * @var array Will be used to generate options of password hardness
     */
    public $randomOptions = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->options = ArrayHelper::merge(['class' => 'form-control'], $this->options);
        $this->randomOptions = $this->randomOptions ?: [
            'weak' => ['label' => Yii::t('hipanel', 'Weak'), 'length' => 8, 'specialchars' => 0],
            'medium' => ['label' => Yii::t('hipanel', 'Medium'), 'length' => 10],
            'strong' => ['label' => Yii::t('hipanel', 'Strong'), 'length' => 14],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $this->registerClientScript();

        $html = Html::activePasswordInput($this->model, $this->attribute, $this->options);
        $html .= '<div class="input-group-btn">';
        $html .= Html::button(Html::tag('span', '', ['class' => 'glyphicon glyphicon-eye-open']), [
            'class' => 'btn btn-default show-password' . ($this->options['disabled'] ? ' disabled' : ''),
            'tabindex' => '-1',
        ]);

        if ($this->randomGenerator) {
            $html .= Html::button(Yii::t('hipanel', 'Random') . '&nbsp;<span class="caret"></span>', [
                'class' => 'btn btn-default dropdown-toggle' . ($this->options['disabled'] ? ' disabled' : ''),
                'data-toggle' => 'dropdown',
                'aria-expanded' => 'false',
                'tabindex' => '-1',
            ]);
            $html .= Html::ul($this->randomOptions, [
                'class' => 'dropdown-menu',
                'role' => 'menu',
                'item' => function ($item) {
                    return Html::tag('li', Html::a($item['label'], '#', [
                        'data' => [
                            'length' => $item['length'],
                            'specialchars' => $item['specialchars'],
                        ],
                        'class' => 'random-passgen',
                    ]));
                },
            ]);
        }
        $html .= '</div>';

        echo Html::tag('div', $html, ['class' => 'input-group', 'id' => $this->id]);
    }

    /**
     * Register widget asset.
     */
    public function registerClientScript()
    {
        $view = $this->getView();
        $selector = '#' . $this->id;
        $view->registerJs(new JsExpression(<<< JS
            $.fn.passwordInput = function(options) {
                
                var settings = $.extend({}, options);
                
                var _this = this;
                
                var randomString = function (length, specialChars) {
                    var specialChars = specialChars !== undefined ? specialChars : true;
                    var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';
                    chars = chars.split('');
                    if (!length) length = Math.floor(Math.random() * chars.length);
                    var str = '';
                    for (var i = 0; i < length; i++) str += chars[Math.floor(Math.random() * chars.length)];
                    if (specialChars) {
                        specialChars = '!@#$%^&*()\-_=+{};:,<.>'.split('');
                        var specialChar = specialChars[Math.floor(Math.random() * specialChars.length)];
                        str = str.replace(/.$/, specialChar);
                    }
                    return str;
                }
                
                _this.find('.random-passgen').each(function() {
                    $(this).click(function(event) { 
                        event.preventDefault();
                        var value = randomString($(this).data('length'), $(this).data('specialchars')!=0);
                        _this.find('.show-password').trigger('click', [value]);
                    });
                });
                
                _this.find('.show-password').each(function() {
                    $(this).click(function(event, value) {
                        var input = _this.find('input');
                        if ($(this).hasClass('disabled')) {
                            return true;
                        }

                        var type = input.attr('type');

                        if (value) input.val(value).select();

                        if (type == 'password' || value) {
                            input.attr('type', 'text');
                            $(this).find('span').removeClass('glyphicon-eye-open').addClass('glyphicon-eye-close');
                        } else {
                            input.attr('type', 'password');
                            $(this).find('span').removeClass('glyphicon-eye-close').addClass('glyphicon-eye-open');
                        }
                    });
                });
                    
                  
            };

            $('{$selector}').passwordInput();
JS
        ), \yii\web\View::POS_READY);

        return true;
    }
}
