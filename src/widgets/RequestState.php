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
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

class RequestState extends Widget
{
    public $module;

    /**
     * @var \hiqdev\hiart\ActiveRecord
     */
    public $model;

    /**
     * @var array additional options to be passed to the JS plugin call
     */
    public $clientOptions = [];

    /**
     * @var string default selector of wrapper with state labels. Will be passed to JS plugin call.
     */
    public $elementSelector = '#content-pjax';

    public function init()
    {
        parent::init();
        if ($this->model->request_state_label) {
            $this->model->request_state_label = Yii::t('hipanel', $this->model->request_state_label);
        }

        if ($this->model->state_label) {
            $this->model->state_label = Yii::t('hipanel', $this->model->state_label);
        }

        if (empty($this->module)) {
            $this->module = $this->model->formName();
        }
    }

    public function run()
    {
        if ($this->model->request_state) {
            $icon = Html::tag('i', '', [
                'class' => ($this->model->request_state !== 'error') ? 'fa fa-circle-o-notch fa-spin' : 'fa fa-exclamation-triangle text-danger',
            ]);

            $res = Html::tag('span', $icon . ' ' . $this->model->request_state_label, [
                'class' => 'objectState',
                'data'  => [
                    'id'         => $this->model->id,
                    'module'     => $this->module,
                    'norm_state' => $this->model->state_label,
                    'with_href'  => 0,
                ],
            ]);
        } else {
            $res = Html::tag('span', $this->model->state_label);
        }

        RequestStateAsset::register(Yii::$app->getView());

        $options   = Json::encode(array_merge(['module' => $this->module], $this->clientOptions));
        $plugin_id = 'objectStateWatcher-' . $this->module;
        Yii::$app->getView()->registerJs("$('{$this->elementSelector}').objectsStateWatcher($options);", \yii\web\View::POS_READY, $plugin_id);

        return $res;
    }
}
