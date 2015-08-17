<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\JsExpression;
use yii\widgets\Breadcrumbs;
use yii\widgets\PjaxAsset;

class Pjax extends \yii\widgets\Pjax
{
    public function init () {
        \yii\widgets\Pjax::init();
        Alert::widget();
        if ($this->requiresPjax()) {
            $this->addBreadcrumbs();
        }
    }

    public function registerClientScript () {
        $id = $this->options['id'];
        $this->clientOptions['push'] = $this->enablePushState;
        $this->clientOptions['replace'] = $this->enableReplaceState;
        $this->clientOptions['timeout'] = $this->timeout;
        $this->clientOptions['scrollTo'] = $this->scrollTo;
        $options = Json::encode($this->clientOptions);
        $linkSelector = Json::encode($this->linkSelector !== null ? $this->linkSelector : '#' . $id . ' a');
        $formSelector = Json::encode($this->formSelector !== null ? $this->formSelector : '#' . $id . ' form[data-pjax]');
        $view = $this->getView();
        PjaxAsset::register($view);
        $js = "jQuery(document).pjax($linkSelector, \"#$id\", $options);";
        $js .= "\njQuery(document).on('submit', $formSelector, function (event) {
            var options = $options;
            options['beforeReplace'] = function () {
                $('.modal-backdrop').remove();
                /// Dirty crutch for https://github.com/twbs/bootstrap/issues/16320
            }
            var \$form = $(event.target);
            if (\$form.data('pjaxPush') !== undefined) {
                options.push = \$form.data('pjaxPush')
            }
            jQuery.pjax.submit(event, '#$id', options);
            $('.modal-backdrop').remove();
        });";
        $view->registerJs($js);
        $view->registerJs('$.pjax.defaults.timeout = 0;');
    }

    public function addBreadcrumbs () {
        $view = \Yii::$app->getView();

        // No need to add breadcrumbs, if they are completely empty
        if (!isset($view->params['breadcrumbs']) || $view->params['breadcrumbs']->count() == 0) {
            return null;
        }

        $header      = Html::tag('h1', $view->title . ($view->params['subtitle'] ? Html::tag('small', $view->params['subtitle']) : ''));
        $breadcrumbs = Breadcrumbs::widget([
            'homeLink'     => [
                'label' => '<i class="fa fa-dashboard"></i> ' . \Yii::t('app', 'Home'),
                'url'   => '/'
            ],
            'encodeLabels' => false,
            'tag'          => 'ol',
            'links'        => isset($view->params['breadcrumbs']) ? $view->params['breadcrumbs'] : []
        ]);
        echo Html::tag('div', $header . $breadcrumbs, ['id' => 'breadcrumb']);

        \Yii::$app->getView()->registerJs(new JsExpression(<<< JS
            $('.content-header li a').on('click', function (event) {
                var container = $('#{$this->id}');
                $.pjax.click(event, {container: container});
            });
            //$('.content-header .breadcrumb').html($('#{$this->id} #breadcrumb').html());
            $('.content-header .breadcrumb').replaceWith($('#{$this->id} #breadcrumb').html());
            $('#{$this->id} #breadcrumb').remove();
JS
        ), \yii\web\View::POS_READY);
    }
}
