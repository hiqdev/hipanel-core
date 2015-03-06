<?php

/**
 * Created by PhpStorm.
 * User: SilverFire
 * Date: 27.01.2015
 * Time: 13:57
 */
namespace frontend\components\widgets;

use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\Breadcrumbs;

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
        \yii\widgets\Pjax::registerClientScript();
        \Yii::$app->getView()->registerJs('$.pjax.defaults.timeout = 0;');
    }

    public function addBreadcrumbs () {
        $view = \Yii::$app->getView();

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
    $('.content-header').html($('#{$this->id} #breadcrumb').html());
    $('#{$this->id} #breadcrumb').remove();
JS
            , \yii\web\View::POS_READY));
    }
}