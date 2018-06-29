<?php

namespace hipanel\widgets\combo;

use hiqdev\combo\Combo;
use ReflectionClass;
use yii\base\InvalidConfigException;
use yii\bootstrap\Html;
use yii\web\View;

class InternalObjectCombo extends Combo
{
    /** {@inheritdoc} */
    public $type = 'hipanel/object';

    /** {@inheritdoc} */
    public $name;

    /** {@inheritdoc} */
    public $url;

    /** {@inheritdoc} */
    public $_return;

    /** {@inheritdoc} */
    public $_rename;

    public $_primaryFilter;

    public $objects = [];

    public $class_attribute;

    public $class_attribute_name;

    private $attributes = [];

    public function init()
    {
        if (empty($this->objects)) {
            throw new InvalidConfigException('Property `objectsOptions` is required for class ObjectCombo.');
        }
        $this->inputOptions = ['data-object-selector-field' => true, 'class' => 'object-selector-select'];
        $this->registerSpecialAssets();
        $this->findCurrentAttributes();
        $this->generateConfigs();
        parent::init();
        $this->registerChangerScript();
        $this->applyDefaultAttributes();
    }

    private function generateConfigs()
    {
        foreach ($this->objects as $type => $options) {
            $this->applyConfigByObjectClassName($type);
        }

    }

    private function registerChangerScript()
    {
        $changerId = Html::getInputId($this->model, $this->class_attribute_name);
        $inputId = $this->inputOptions['id'];

        $this->view->registerJs("initObjectSelectorChanger('{$changerId}', '{$inputId}')");
    }

    private function applyConfigByObjectClassName($class)
    {
        $options = $this->objects[$class];
        if ($options['comboOptions']) {
            foreach ($this->attributes as $attribute) {
                if (isset($options['comboOptions'][$attribute->name])) {
                    $this->{$attribute->name} = $options['comboOptions'][$attribute->name];
                }
            }
        }
        $this->registerClientConfig();
        $varName = strtolower($this->model->formName()) . '_object_id_' . $class;
        $id = $this->configId;
        $this->view->registerJsVar($varName, $id, View::POS_END);
    }

    private function findCurrentAttributes()
    {
        $this->attributes = array_filter((new ReflectionClass(get_class($this)))->getProperties(), function ($attr) {
            return $attr->class === get_class($this);
        });
    }

    private function applyDefaultAttributes()
    {
        $this->applyConfigByObjectClassName($this->class_attribute ?: 'client');
    }

    private function registerSpecialAssets()
    {
        // Fix validation styles
        $this->view->registerCss("
            .form-group.has-error .select2-selection { border-color: #dd4b39; box-shadow: none; }
            .form-group.has-success .select2-selection { border-color: #00a65a; box-shadow: none; }
            select.object-selector-select:not([data-select2-id]) { display: none; }
        ");
        $this->view->registerJs(<<<'JS'
            (function( $ ){

                var originalDynamicForm = $.fn.yiiDynamicForm;
                
                var methods = {
                    addItem : function(widgetOptions, e, elem) { 
                        originalDynamicForm('addItem', widgetOptions, e, elem);
                        var count = elem.closest('.' + widgetOptions.widgetContainer).find(widgetOptions.widgetItem).length;

                        // Reinit ObjectSelectorChanger after added new item
                        if (count < widgetOptions.limit) {
                            var objectSelectorInputs = $($newclone).find('[data-object-selector-field]');
                            if (objectSelectorInputs.length > 0) {
                                objectSelectorInputs.each(function() {
                                    var objectInputId = $(this).attr('id');
                                    var changerInputId = $(this).prev('select').attr('id');
                                    initObjectSelectorChanger(changerInputId, objectInputId); 
                                });
                            }
                        } 
                    }
                };
                
                $.fn.yiiDynamicForm = function(method) {
                    if (method === 'addItem') {
                        return methods[ method ].apply( this, Array.prototype.slice.call( arguments, 1 ));
                    } else {
                        originalDynamicForm.apply(this, arguments);        
                    }
                }
                
            })(window.jQuery);
JS
        );
        $this->view->registerJs(<<<JS
            function initObjectSelectorChanger(changerInputId, objectInputId) {
                $('#' + changerInputId).change(function(e) {
                    var regexID = /^(.+?)([-\d-]{1,})(.+)$/i;
                    var matches = objectInputId.match(regexID);
                    var combo = $('#' + objectInputId).closest('form').combo();
                    if (combo) {
                        combo.register('#' + objectInputId, window[matches[1] + '_object_id_' + e.target.value]);
                        $('#' + objectInputId).val(null).trigger('change');
                    }
                });
            }
JS
        );
    }
}
