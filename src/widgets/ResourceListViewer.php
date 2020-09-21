<?php

namespace hipanel\widgets;

use hipanel\assets\BootstrapDatetimepickerAsset;
use hipanel\models\Resource;
use Yii;
use yii\base\ViewContextInterface;
use yii\base\Widget;
use yii\data\DataProviderInterface;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\View;

class ResourceListViewer extends Widget
{
    public DataProviderInterface $dataProvider;

    public ViewContextInterface $originalContext;

    public $searchModel;

    public $uiModel;

    public function run(): string
    {
        $this->registerJs();
        $this->registerCss();

        return $this->render('ResourceListViewer', [
            'dataProvider' => $this->dataProvider,
            'originalContext' => $this->originalContext,
            'searchModel' => $this->searchModel,
            'uiModel' => $this->uiModel,
            'model' => new Resource(),
        ]);
    }

    private function registerJs(): void
    {
        BootstrapDatetimepickerAsset::register($this->view);
        $ids = Json::encode(ArrayHelper::getColumn($this->dataProvider->getModels(), 'id'));
        $csrf_param = Yii::$app->request->csrfParam;
        $csrf_token = Yii::$app->request->csrfToken;
        $locale = Yii::$app->language;
        $this->view->registerJs(/** @lang JavaScript */ <<<"JS"
(() => {
  const ids = {$ids};
  
  const getRange = () => {
    const date = getDate();
    return {
      time_from: date ? moment(date).startOf('month').format('YYYY-MM-DD') : '',
      time_till: date ? moment(date).endOf('month').format('YYYY-MM-DD') : ''
    }
  }
  const saveDate = date => {
    localStorage.setItem('resources-list-date', date.format('L'));
  }
  const getDate = () => {
    return moment().subtract(1, 'month');
    return localStorage.getItem('resources-list-date');
  }
  const dateInput = $('input[name="date-range"]');
  const {time_from, time_till} = getRange();
    
  dateInput.datetimepicker({
    // date: getDate() ? moment(getDate()) : moment().subtract(1, 'month'),
    date: moment().subtract(1, 'month'),
    locale: '{$locale}',
    viewMode: 'months',
    format: 'MMMM YYYY',
    maxDate: moment().subtract(1, 'month')
  });
  dateInput.datetimepicker().on('dp.update', evt => {
    $('td[data-type]').html('<div class="spinner"><div class="rect1"></div><div class="rect2"></div><div class="rect3"></div><div class="rect4"></div><div class="rect5"></div></div>');
    const date = moment(evt.date);
    // saveDate(date);
    fetchResources(ids, date.startOf('month').format('YYYY-MM-DD'), date.endOf('month').format('YYYY-MM-DD'));
  });
  
  const fetchResources = async (ids, time_from, time_till) =>  {
    const formData = new FormData();
    formData.append('object_ids', ids);
    if (time_from.length !== 0 && time_till.length !== 0) {
      formData.append('time_from', time_from);
      formData.append('time_till', time_till);
    }
    formData.append('{$csrf_param}', '{$csrf_token}');
    
    try {
      const response = await fetch('fetch-resources', {
        method: 'POST',
        body: formData
      });
      const result = await response.json();
      Object.entries(result).forEach(entry => {
        const [id, resources] = entry;
        Object.entries(resources).forEach(resource => {
          const [type, data] = resource;
          const cell = document.querySelector('tr[data-key="' + id + '"] > td[data-type="' + type + '"]');
          if (!!cell) {
            cell.innerHTML = parseFloat(data.amount).toFixed(3) + ' ' + data.unit;
          }
        });
      });
      const not_counted = document.createElement('span');
      not_counted.classList.add('text-danger');
      not_counted.appendChild(document.createTextNode('not counted'));
      document.querySelectorAll('tr[data-key] .spinner').forEach(node => {
        node.parentNode.replaceChild(not_counted.cloneNode(true), node);
      })
    } catch (error) {
      hipanel.notify.error(error.message);
    }
  }
  fetchResources(ids, time_from, time_till);
})();
JS
    , View::POS_READY);
    }

    private function registerCss()
    {
        $this->view->registerCss(<<<CSS
.spinner {
  width: 50px;
  height: 10px;
  text-align: center;
  font-size: 10px;
  display: inline-block;
}

.spinner > div {
  background-color: #b8c7ce;
  height: 100%;
  width: 6px;
  display: inline-block;
  margin-right: .1rem;
  
  -webkit-animation: sk-stretchdelay 1.2s infinite ease-in-out;
  animation: sk-stretchdelay 1.2s infinite ease-in-out;
}

.spinner .rect2 {
  -webkit-animation-delay: -1.1s;
  animation-delay: -1.1s;
}

.spinner .rect3 {
  -webkit-animation-delay: -1.0s;
  animation-delay: -1.0s;
}

.spinner .rect4 {
  -webkit-animation-delay: -0.9s;
  animation-delay: -0.9s;
}

.spinner .rect5 {
  -webkit-animation-delay: -0.8s;
  animation-delay: -0.8s;
}

@-webkit-keyframes sk-stretchdelay {
  0%, 40%, 100% { -webkit-transform: scaleY(0.4) }  
  20% { -webkit-transform: scaleY(1.0) }
}

@keyframes sk-stretchdelay {
  0%, 40%, 100% { 
    transform: scaleY(0.4);
    -webkit-transform: scaleY(0.4);
  }  20% { 
    transform: scaleY(1.0);
    -webkit-transform: scaleY(1.0);
  }
}
CSS
        );
    }
}