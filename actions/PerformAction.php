<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use frontend\components\CrudController;
use frontend\components\helpers\ArrayHelper as AH;
use frontend\components\helpers\ArrayHelper;
use hiqdev\hiar\ActiveRecord;
use hiqdev\hiar\Collection;
use hiqdev\hiar\HiResException;
use Yii;
use yii\base\Controller;
use yii\base\Exception;
use yii\base\InvalidCallException;
use yii\db\IntegrityException;
use yii\helpers\Json;

/**
 * @property mixed resultBehaviours
 */
class PerformAction extends \yii\base\Action
{
    /**
     * @var CrudController|Controller|\yii\web\Controller the controller that owns this action
     */
    public $controller;

    /**
     * @var array the configuration of the action
     */
    public $options = [];

    /**
     * @var Collection collection
     */
    public $collection;

    /**
     * @var array to store the result of the save operation
     */
    public $saveResult;

    /**
     * @var array the options that will be passed to the [[Collection]] object
     */
    public $collectionOptions;

    public function init () {
        $this->initOptions();
    }

    public function run () {
        $this->createCollection();
        $this->loadCollection();
        if (!$this->collection->isEmpty()) {
            $this->saveCollection();
        }

        return $this->formatResult();
    }

    public function initOptions () {
        $defaults = [
            'controller' => Yii::$app->controller,
            'scenario'   => $this->id,
            'success'    => [
                'message' => 'The {scenario} was successful',
            ],
            'error'      => [
                'message' => 'An error occurred during {scenario}',
            ],
        ];

        $this->options           = AH::merge($defaults, $this->options);
        $this->collectionOptions = AH::merge([
            'class'    => Collection::className(),
            'model'    => $this->controller->newModel(),
            'scenario' => $this->options['scenario']
        ], $this->collectionOptions);

        $this->initResultBehaviours();
    }

    public $_resultBehaviours;

    public function getResultBehaviours () {
        if (empty($this->_resultBehaviours)) {
            return [
                'ajax'         => [
                    '*' => ['return', 'json', 'addFlash' => false]
                ],
                'pjax'         => [
                    '*' => ['action', ['view', 'id' => '{id}'], 'addFlash' => true],
                ],
                'html'         => [
                    '*' => ['action', ['view', 'id' => '{id}'], 'addFlash' => true]
                ],
                'editableAjax' => [
                    '*' => [
                        'renderJson',
                        'format' => function ($model, $saveResult) {
                            $return = ['id' => $model->id];
                            if ($saveResult['success'] === false) {
                                $return['message'] = $saveResult['message'];
                            }

                            return $return;
                        },
                        'reduce' => true,
                    ]
                ]
            ];
        } else {
            return $this->_resultBehaviours;
        }
    }

    public function setResultBehaviours ($value) {
        $this->_resultBehaviours = $value;
    }

    public function initResultBehaviours () {
        $behaviours = $this->getResultBehaviours();

        foreach (['success', 'error'] as $group) {
            $rules = ArrayHelper::merge($this->options['result'], $this->options[$group]['result']);
            foreach ($rules as $condition => $rule) {
                $method = '*';

                $condition = explode(' ', $condition); /// Try to extract request method from the behaviour description
                if ($condition[1]) {
                    $method = $condition[0];
                    $type   = $condition[1];
                } else {
                    $type = $condition[0];
                }

                $behaviours[$type][$method][$group] = $rule;
            }
        }

        $this->_resultBehaviours = $behaviours;
    }

    public function createCollection () {
        return $this->collection = Yii::createObject($this->collectionOptions);
    }

    public function loadCollection () {
        return $this->collection->load();
    }

    public function saveCollection () {
        try {
            return $this->saveResult = $this->collection->save();
        } catch (HiResException $e) {
            return $this->saveResult = $e->getMessage();
        }
    }

    public function isSaveEmpty () {
        return $this->saveResult === null;
    }

    public function isSaveSuccess () {
        return $this->collection->hasErrors();
    }

    public function formatResult () {
        $results = [];
        foreach ($this->collection->models as $model) {
            /* @var $model ActiveRecord */
            $saveResult = $this->createSaveResult($model);
            $behaviour  = $this->getBehaviour($saveResult);
            if ($behaviour['addFlash'] !== false) {
                $this->addFlash($model, $saveResult);
            }
            $result = $this->runBehaviour($behaviour, $saveResult, $model);
            if ($behaviour['reduce']) {
                return $result;
            } else {
                $results[$model->getPrimaryKey()] = $result;
            }
        }

        return $results;
    }

    /**
     * @param ActiveRecord $model
     * @return array
     */
    public function createSaveResult ($model) {
        if ($model->hasErrors()) {
            $saveResult = ['success' => false, 'message' => $model->getErrors()]; /// For validation errors
        } elseif (is_bool($this->saveResult)) {
            $saveResult = ['success' => $this->saveResult];
        } elseif (is_string($this->saveResult)) {
            $saveResult = ['success' => false, 'message' => $this->saveResult];
        } else {
            $saveResult = ['success' => true];
        }
        $saveResult['class']   = $saveResult['type'] ? 'success' : 'error';
        $saveResult['message'] = $saveResult['message'] ?: Yii::t('app', $this->options[$saveResult['success']]['message'], $model->getAttributes());

        return $saveResult;
    }

    public function getBehaviour ($saveResult) {
        $type       = $this->getRequestType();
        $method     = $this->getRequestMethod();
        $behaviours = $this->resultBehaviours;

        if (isset($behaviours[$type][$method][$saveResult['class']])) {
            return $behaviours[$type][$method][$saveResult['class']];
        } elseif (isset($behaviours[$type]['*'][$saveResult['class']])) {
            return $behaviours[$type]['*'][$saveResult['class']];
        } elseif (isset($behaviours[$type]['*'])) {
            return $behaviours[$type]['*'];
        } else {
            return false;
        }
    }

    public function runBehaviour ($rule, $saveResult, $model) {
        $format    = ArrayHelper::remove($rule, 'format', null);
        $behaviour = array_shift($rule);
        $params    = array_shift($rule);

        if ($format instanceof \Closure) {
            $data = call_user_func($format, $model, $saveResult);
        } else {
            $data = (array)$model;
        }

        if ($behaviour === 'return') {
            /*
             * ['return', 'json']
             * ['return', 'json', 'format' => function ($model, $success) { return ['message' => $success ? '' : $model->getFirstError()]; } ]]
             * ['return', 'format' => function ($model, $success) { return 123; }]
             */
            if ($params === 'json') {
                return Json::encode($data);
            } else {
                return $data;
            }
        } elseif ($behaviour === 'render') { /// ['render', ['view', 'id' => 123]]
            return $this->controller->render($params);
        } elseif ($behaviour === 'renderJson') {
            /// ['renderJson', ['view', 'id' => 123], 'format' => function ($model, $success) {  return ['message' => $success ? '' : $model->getFirstError()]; ]
            return $this->controller->renderJson($data);
        } elseif ($behaviour === 'redirect') {
            /// ['redirect', 'url/to']
            return $this->controller->redirect($params);
        } elseif ($behaviour === 'action') {
            /// ['view', ['someparam' => 1]]
            $action = $params[0];
            $params = $params[1];

            return $this->controller->runAction($action, $params);
        } elseif ($behaviour === 'custom' && $params instanceof \Closure) { /// function ($action, $model) { return $model; }
            return call_user_func($params, $this, $model);
        } else {
            throw new InvalidCallException('Requested method action is not allowed!');
        }
    }

    public function getRequestType () {
        $request = Yii::$app->request;
        if ($request->isPjax) {
            return 'pjax';
        } elseif ($request->isAjax && array_key_exists('application/json', $request->getAcceptableContentTypes())) {
            if ($request->post('hasEditable')) {
                return 'editableAjax';
            } else {
                return 'ajax';
            }
        } else {
            return 'html';
        }
    }

    public function addFlash ($model, $saveResult) {
        Yii::$app->session->addFlash($saveResult['class'], [
            'title' => $model->getPrimaryValue(),
            'text'  => $saveResult['message']
        ]);
    }

    public function getRequestMethod () {
        return Yii::$app->request->method;
    }
}
