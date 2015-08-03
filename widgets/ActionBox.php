<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Url;

class ActionBox extends Box
{
    public $model;

    public $bulk = false;

    public function run() {
        parent::run();
        $this->registerClientScript();
    }

    private function registerClientScript() {
        $view = $this->getView();
        $view->registerJs(<<<JS
        var checkboxes = $('input[type="checkbox"]');
        var bulkcontainer = $('.box-bulk-actions fieldset');
        checkboxes.on('ifChecked ifUnchecked', function(event) {
            if (event.type == 'ifChecked') {
                console.log('123');
                bulkcontainer.prop('disabled', false);
            } else if (!checkboxes.filter(':checked').length > 0) {
                console.log('321');
                bulkcontainer.prop('disabled', true);
            }
        });
JS
        , $view::POS_READY);
    }

    public function beginActions() {
        print Html::beginTag('div', ['class' => sprintf('box-actions %s', ($this->bulk) ? 'pull-left' : '')]) . "\n";
    }

    public function endActions() {
        print "\n" . Html::endTag('div');
    }

    public function beginBulkActions() {
        if ($this->bulk == false)
            throw new InvalidConfigException("'bulk' property is false, turn this on ('true' statement), if you want use bulk actions.");

        print Html::beginTag('div', ['class' => 'pull-right box-bulk-actions']) . "\n";
    }

    public function endBulkActions() {
        print "\n" . Html::endTag('div');
        print Html::tag('div', '', ['class' => 'clearfix']);
    }

    public function renderCreateButton($text)
    {
        return Html::a($text, ['create'], ['class' => 'btn btn-success']);
    }

    public function renderSearchButton() {
        return AdvancedSearch::renderButton();
    }

    public function beginSearchForm() {
        return AdvancedSearch::begin(['model' => $this->model]);
    }

    public function printSearchForm()
    {
        $search = $this->beginSearchForm();
        print Yii::$app->view->render('_search', compact('search'));
        $search::end();
    }

    public function renderBulkActions(array $options)
    {
        $this->beginBulkActions();
        print BulkButtons::widget(array_merge([
            'model' => $this->model,
        ], $options));
        $this->endBulkActions();
    }

    public function renderDeleteButton($text = null)
    {
        $text = $text ?: Yii::t('app', 'Delete');
        return Html::submitButton($text, [
            'class'         => 'btn btn-danger',
            'form'          => $this->bulkFormId(),
            'formmethod'    => 'POST',
            'formaction'    => Url::to('delete'),
        ]);
    }

    public function bulkFormId()
    {
        return 'bulk-' . Inflector::camel2id($this->model->formName());
    }
}
