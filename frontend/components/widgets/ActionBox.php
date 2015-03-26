<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 23.03.15
 * Time: 18:30
 */
namespace frontend\components\widgets;

use yii\base\InvalidConfigException;
use yii\helpers\Html;

class ActionBox extends Box
{
    public $bulk = false;

    public function beginActions() {
        print Html::beginTag('div', ['class' => sprintf('box-actions %s', ($this->bulk) ? 'pull-left' : '')]) . "\n";
    }

    public function endActions() {
        print "\n" . Html::endTag('div');
    }

    public function beginBulkActions() {
        if ($this->bulk == false)
            throw new InvalidConfigException("'bulk' property is false, turn this on ('true' statement), if you want use bulk actions.");
        print Html::beginTag('div', ['class' => 'pull-right box-bulk-actions', 'style' => 'display: none;']) . "\n";
    }

    public function endBulkActions() {
        print "\n" . Html::endTag('div');
        print Html::tag('div', '', ['class' => 'clearfix']);
    }

    public function run() {
        parent::run();
        $this->registerClientScript();
    }

    private function registerClientScript() {
        $view = $this->getView();
        $view->registerJs(<<<JS
        var checkboxes = $('input[type="checkbox"]');
        var bulkcontainer = $('.box-bulk-actions');
        checkboxes.on('ifChecked ifUnchecked', function(event) {
            if (event.type == 'ifChecked') {
                bulkcontainer.toggle(true);
            } else if (!checkboxes.filter(':checked').length > 0) {
                bulkcontainer.toggle(false);
            }
        });
JS
        , $view::POS_READY);
    }
}