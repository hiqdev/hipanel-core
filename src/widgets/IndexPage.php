<?php

namespace hipanel\widgets;

use hipanel\base\OrientationStorage;
use Yii;
use yii\base\Widget;
use yii\base\InvalidParamException;
use yii\bootstrap\ButtonDropdown;
use yii\helpers\Html;
use yii\helpers\Url;

class IndexPage extends Widget
{
    public $model;

    public $dataProvider;

    public $contents = [];

    protected $_current = null;

    public function beginContent($name, $params = [])
    {
        if ($this->_current) {
            throw new InvalidParamException('Already started content for ' . $this->_current);
        }
        $this->_current = $name;
        ob_start();
        ob_implicit_flush(false);
    }

    public function endContent()
    {
        if (!$this->_current) {
            throw new InvalidParamException('Not started content');
        }
        $this->contents[$this->_current] = ob_get_contents();
        ob_end_clean();
        $this->_current = null;
    }

    public function renderContent($name)
    {
        return $this->contents[$name];
    }
    
    public function run()
    {
        return $this->render($this->getOrientationStorage());
    }

    public function getOrientationStorage()
    {
        return OrientationStorage::instantiate()->get(Yii::$app->controller->getRoute());
    }

    public function renderSearchForm(array $data = [], $advancedSearchOptions = [])
    {
        ob_start();
        ob_implicit_flush(false);
        try {
            $search = $this->beginSearchForm($advancedSearchOptions);
            foreach (['per_page', 'representation'] as $key) {
                echo Html::hiddenInput($key, Yii::$app->request->get($key));
            }
            echo Yii::$app->view->render('_search', array_merge(compact('search'), $data));
            $search->end();
        } catch (\Exception $e) {
            ob_end_clean();
            throw $e;
        }

        return ob_get_clean();
    }

    public function beginSearchForm($options = [])
    {
        return AdvancedSearch::begin(array_merge(['model' => $this->model], $options));
    }

    public function renderSearchButton()
    {
        return AdvancedSearch::renderButton() . "\n";
    }

    public function renderPerPage()
    {
        return ButtonDropdown::widget([
            'label' => Yii::t('app', 'Per page') . ': ' . (Yii::$app->request->get('per_page') ?: 25),
            'options' => ['class' => 'btn-default btn-sm'],
            'dropdown' => [
                'items' => [
                    ['label' => '25',  'url' => Url::current(['per_page' => null])],
                    ['label' => '50',  'url' => Url::current(['per_page' => 50])],
                    ['label' => '100', 'url' => Url::current(['per_page' => 100])],
                    ['label' => '200', 'url' => Url::current(['per_page' => 200])],
                    ['label' => '500', 'url' => Url::current(['per_page' => 500])],
                ],
            ],
        ]);
    }

    public function renderRepresentation()
    {
        $representation = Yii::$app->request->get('representation') ?: 'common';

        return ButtonDropdown::widget([
            'label' => Yii::t('synt', 'View') . ': ' . Yii::t('app', $representation),
            'options' => ['class' => 'btn-default btn-sm'],
            'dropdown' => [
                'items' => [
                    ['label' => Yii::t('app', 'common'), 'url' => Url::current(['representation' => null])],
                    ['label' => Yii::t('app', 'report'), 'url' => Url::current(['representation' => 'report'])],
                ],
            ],
        ]);
    }

    public function renderSorter(array $options)
    {
        return LinkSorter::widget(array_merge([
            'show'  => true,
            'sort'  => $this->dataProvider->getSort(),
            'buttonClass' => 'btn btn-default dropdown-toggle btn-sm'
        ], $options));
    }

    public function getViewPath()
    {
        return parent::getViewPath() . DIRECTORY_SEPARATOR . (new \ReflectionClass($this))->getShortName();
    }
}
