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

use Yii;
use yii\helpers\Html;

/**
 * Label widget displays bootstrap colored label.
 *
 * Usage:
 * EventLog::widget([
 *      'statuses' => $model->statuses,
 * ]);
 *
 * @var string
 */
class EventLog extends \yii\base\Widget
{
    /**
     * @var array
     */
    public $statuses = [];

    public function run()
    {
        echo $this->renderData();
    }

    protected function renderData()
    {
        if (empty($this->statuses)) {
            return Yii::t('hipanel', 'No events were recorded');
        }

        $res = Html::beginTag('table', ['class' => 'table table-condensed']);
        $res .= Html::beginTag('tr');
        $res .= Html::tag('th', Yii::t('hipanel', 'Event'));
        $res .= Html::tag('th', Yii::t('hipanel', 'Time'));
        $res .= Html::endTag('tr');
        foreach ($this->statuses as $status => $time) {
            $res .= Html::beginTag('tr');
            $res .= Html::tag('td', Yii::t('hipanel:synt', $status));
            $res .= Html::tag('td', Yii::$app->formatter->asDatetime($time));
            $res .= Html::endTag('tr');
        }
        $res .= Html::endTag('table');

        return $res;
    }
}
