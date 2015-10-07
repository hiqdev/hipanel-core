<?php

namespace hipanel\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\JsExpression;

class AjaxModal extends \yii\bootstrap\Modal
{
    protected $_actionUrl;

    public function setActionUrl($url)
    {
        $this->_actionUrl = $url;
    }

    public function getActionUrl()
    {
        return $this->_actionUrl ? Url::to($this->_actionUrl) : Url::to($this->scenario);
    }

    protected $_modalFormId;

    public function setModalFormId($id)
    {
        $this->_modalFormId = $id;
    }

    public function getModalFormId()
    {
        return $this->_modalFormId ?: $this->scenario . '-form';
    }

    protected $_errorText;

    public function setErrorText($text)
    {
        $this->_errorText = $text;
    }

    public function getErrorText()
    {
        return $this->_errorText ? : Yii::t('app', 'An error occurred. Try again later.');
    }

    protected $_successText;

    public function setSuccessText($text)
    {
        $this->_successText = $text;
    }

    public function getSuccessText()
    {
        return $this->_successText ? : Yii::t('app', 'Settings saved');
    }

    protected $_loadingText;

    public function setLoadingText($text)
    {
        $this->_loadingText = $text;
    }

    public function getLoadingText()
    {
        return $this->_loadingText ?: Yii::t('app', 'loading') . '...';
    }

    public $scenario;

    public function init()
    {
        if (!$this->scenario) {
            throw new InvalidConfigException("Attribute 'scenario' is required");
        }

        $this->initAdditionalOptions();
        $this->initPerformPjaxSubmit();
        parent::init();
    }

    protected function initPerformPjaxSubmit()
    {
        Yii::$app->view->registerJs(<<<JS
//            jQuery(document).on('pjax:beforeSend', function() {
//                jQuery('form[data-pjax] button').button('{$this->loadingText}');
//            });
//            jQuery(document).on('pjax:end', function() {
//                jQuery('form[data-pjax] button').button('reset');
//            });

            jQuery(document).on('submit', '#{$this->modalFormId}', function(event) {
                event.preventDefault();
                var form = jQuery(this);
                var btn = jQuery('form[data-pjax] button').button('{$this->loadingText}');
                jQuery.ajax({
                    url: '{$this->actionUrl}',
                    type: 'POST',
                    //dataType: 'json',
                    timeout: 0,
                    data: form.serialize(),
                    error: function() {
                        new PNotify({
                            title: 'Error',
                            text: "{$this->errorText}",
                            type: 'error',
                            buttons: {
                                sticker: false
                            },
                            icon: false
                        });
                    },
                    success: function() {
                        jQuery('#$this->id').modal('hide');
                        new PNotify({
                            title: 'Success',
                            text: "{$this->successText}",
                            type: 'info',
                            buttons: {
                                sticker: false
                            },
                            icon: false
                        });
                        btn.button('reset');
                    }
                });
            });
JS
        );
    }

    protected function initAdditionalOptions()
    {
        $quotedHtml = Json::htmlEncode($this->loadingHtml);
        $this->clientEvents['show.bs.modal'] = new JsExpression("function() {
            jQuery('#{$this->id} .modal-body').load('{$this->actionUrl}');
        }");
        $this->clientEvents['hidden.bs.modal'] = new JsExpression("function() {
            jQuery('#{$this->id} .modal-body').html({$quotedHtml});
        }");
    }

    protected function renderBodyBegin()
    {
        return Html::beginTag('div', ['class' => 'modal-body']) . $this->loadingHtml;
    }

    public function getLoadingHtml()
    {
        return <<<HTML
        <div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%">
                <span class="sr-only">{$this->loadingText}</span>
            </div>
        </div>
HTML
        ;
    }
}
