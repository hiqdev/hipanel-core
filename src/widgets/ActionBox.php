<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;
use yii\helpers\Inflector;
use yii\helpers\Json;
use yii\helpers\Url;

class ActionBox extends Box
{
    public $model;

    public $dataProvider;

    public $bulk = true;

    public $options = [
        'class' => 'box-info',
    ];

    public function run() {
        parent::run();
        $this->registerClientScript();
    }

    private function registerClientScript() {
        $searchFormId = Json::htmlEncode("#{$this->getBulkFormId()}");
        $view = $this->getView();
        $view->registerJs(<<<JS
        // Checkbox
        var checkboxes = $('table input[type="checkbox"]');
        var bulkcontainer = $('.box-bulk-actions fieldset');
        checkboxes.on('ifChecked ifUnchecked', function(event) {
            if (event.type == 'ifChecked') {
                bulkcontainer.prop('disabled', false);
            } else if (!checkboxes.filter(':checked').length > 0) {
                bulkcontainer.prop('disabled', true);
            }
        });
        // On/Off Actions TODO: reduce scope
        $(document).on('click', '.box-bulk-actions a', function (event) {
            var link = $(this);
            var action = link.data('action');
            var form = $($searchFormId);
            if (action) {
                form.attr({'action': action, method: 'POST'}).submit();
            }
        });
JS
        , $view::POS_READY);
    }

    public function beginActions() {
        print Html::beginTag('div', ['class' => 'box-actions ' . ($this->bulk ? 'pull-left' : '')]) . PHP_EOL;
    }

    public function endActions() {
        print PHP_EOL . Html::endTag('div');
    }

    public function beginBulkActions() {
        if ($this->bulk == false)
            throw new InvalidConfigException("'bulk' property is false, turn this on ('true' statement), if you want use bulk actions.");

        print Html::beginTag('div', ['class' => 'pull-right box-bulk-actions']) . "\n";
        print Html::beginTag('fieldset', ['disabled' => 'disabled']) . "\n";
    }

    public function endBulkActions() {
        print "\n" . Html::endTag('fieldset');
        print "\n" . Html::endTag('div');
        print Html::tag('div', '', ['class' => 'clearfix']);
    }

    public function renderCreateButton($text)
    {
        return Html::a($text, ['create'], ['class' => 'btn btn-success']) . '&nbsp;';
    }

    public function renderSearchButton() {
        return AdvancedSearch::renderButton() . "\n";
    }

    public function beginSearchForm() {
        return AdvancedSearch::begin(['model' => $this->model]);
    }

    public function renderSearchForm(array $data = [])
    {
        ob_start();
        ob_implicit_flush(false);
        try {
            $search = $this->beginSearchForm();
            print Yii::$app->view->render('_search', array_merge(compact('search'), $data));
            $search->end();
        } catch(\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        return ob_get_clean();
    }

    public function renderPerPage()
    {
        return ButtonDropdown::widget([
            'label' => Yii::t('app', 'Per page') . ': ' . (Yii::$app->request->get('per_page') ?: 25),
            'options' => ['class' => 'btn-default'],
            'dropdown' => [
                'items' => [
                    ['label' => '25', 'url' => Url::current(['per_page' => null])],
                    ['label' => '50', 'url' => Url::current(['per_page' => 50])],
                    ['label' => '100', 'url' => Url::current(['per_page' => 100])],
                    ['label' => '200', 'url' => Url::current(['per_page' => 200])],
                    ['label' => '500', 'url' => Url::current(['per_page' => 500])],
                ],
            ],
        ]);
    }

    public function renderSorter(array $options)
    {
        return LinkSorter::widget(array_merge([
            'show'  => true,
            'sort'  => $this->dataProvider->getSort(),
        ], $options));
    }

    public function renderBulkActions(array $options)
    {
        $this->beginBulkActions();
        print BulkButtons::widget(array_merge([
            'model' => $this->model,
        ], $options));
        $this->endBulkActions();
    }

    public function renderBulkButton($text, $action, $color = 'default')
    {
        return Html::submitButton($text, [
            'class'         => "btn btn-$color",
            'form'          => $this->getBulkFormId(),
            'formmethod'    => 'POST',
            'formaction'    => $action,
        ]);
    }

    public function renderDeleteButton($text = null)
    {
        $text = $text ?: Yii::t('app', 'Delete');
        return $this->renderBulkButton($text, 'delete', 'danger');
    }

    public function getBulkFormId()
    {
        return 'bulk-' . Inflector::camel2id($this->model->formName());
    }

    public function beginBulkForm()
    {
        print Html::beginForm('' , 'POST', ['id' => $this->getBulkFormId()]);
    }

    public function endBulkForm()
    {
        print Html::endForm();
    }
}
