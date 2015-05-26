<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\actions;

use hipanel\base\CrudController;
use hiqdev\hiart\ActiveRecord;
use hiqdev\hiart\Collection;
use hiqdev\hiart\HiResException;
use yii\base\Action;
use yii\base\Controller;
use yii\base\InvalidCallException;
use yii\helpers\ArrayHelper;
use yii\helpers\Inflector;
use yii\helpers\Json;
use Yii;

/**
 * @property mixed resultBehaviours
 */
class PerformAction extends Action
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

    /**
     * @var array of request methods, that
     */
    public static $readOnlyRequestMethods = ["GET"];

    public function init()
    {
        $this->initOptions();
    }

    public function run()
    {
        $this->createCollection();
        $this->loadCollection();
        if (!$this->collection->isEmpty()) {
            $this->saveCollection();
        }

        return $this->formatResult();
    }

    public function initOptions()
    {
        $defaults = [
            'controller' => Yii::$app->controller,
            'scenario'   => $this->id,
            'success'    => [
                'message' => '{scenario} was successful',
            ],
            'error'      => [
                'message' => 'An error occurred during {scenario}',
            ],
        ];

        $this->options           = ArrayHelper::merge($defaults, $this->options);
        $this->collectionOptions = ArrayHelper::merge([
            'class'    => Collection::className(),
            'model'    => $this->controller->newModel(),
            'scenario' => $this->options['scenario']
        ], $this->collectionOptions);

        $this->initResultBehaviours();
    }

    public $_resultBehaviours;

    public function getResultBehaviours()
    {
        if (empty($this->_resultBehaviours)) {
            return [
                'editableAjax' => [
                    '*' => [
                        'renderJson',
                        'format'   => function ($model, $saveResult) {
                            $return = ['id' => $model->id];
                            if ($saveResult['success'] === false) {
                                $return['message'] = $saveResult['message'];
                            }

                            return $return;
                        },
                        'addFlash' => false
                    ]
                ]
            ];
        } else {
            return $this->_resultBehaviours;
        }
    }

    public function setResultBehaviours($value)
    {
        $this->_resultBehaviours = $value;
    }

    public function initResultBehaviours()
    {
        $behaviours = $this->getResultBehaviours();

        foreach (['success', 'error'] as $group) {
            $rules = ArrayHelper::merge($this->options['result'], $this->options[$group]['result']);
            foreach ($rules as $condition => $rule) {
                $condition = explode(' ', $condition); /// Try to extract request method from the behaviour description
                if ($condition[1]) {
                    $method = $condition[0];
                    $type   = $condition[1];
                } else {
                    $method = '*';
                    $type   = $condition[0];
                }

                $behaviours[$type][$method][$group] = $rule;
            }
        }

        $this->_resultBehaviours = $behaviours;
    }

    public function createCollection()
    {
        return $this->collection = Yii::createObject($this->collectionOptions);
    }

    public function loadCollection()
    {
        return $this->collection->load();
    }

    public function saveCollection()
    {
        try {
            return $this->saveResult = $this->collection->save();
        } catch (HiResException $e) {
            return $this->saveResult = $e->getMessage();
        }
    }

    public function isSaveEmpty()
    {
        return $this->saveResult === null;
    }

    public function isSaveSuccess()
    {
        return $this->collection->hasErrors();
    }

    public function formatResult()
    {
        $results = [];

        if (!$this->collection->isEmpty()) {
            foreach ($this->collection->models as $model) {
                /* @var $model ActiveRecord */
                $saveResult = $this->createSaveResult($model);
                $behaviour  = $this->getBehaviour($saveResult);
                $this->addFlash($model, $behaviour, $saveResult);
                $result = $this->runBehaviour($behaviour, $saveResult, $model);
                if ($behaviour['bulk']) {
                    $results[$model->getPrimaryKey()] = $result;
                } else {
                    return $result;
                }
            }
        } else {
            $saveResult = $this->createSaveResult();
            $behaviour  = $this->getBehaviour($saveResult);
            $result     = $this->runBehaviour($behaviour, $saveResult);
            if ($behaviour['bulk']) {
                $results[] = $result;
            } else {
                return $result;
            }
        }

        return $results;
    }

    /**
     * @param ActiveRecord $model
     * @return array
     */
    public function createSaveResult($model = null)
    {
        if ($model !== null && $model->hasErrors()) {
            $saveResult = ['success' => false, 'message' => $model->getErrors()]; /// For validation errors
        } elseif (is_bool($this->saveResult)) {
            $saveResult = ['success' => $this->saveResult];
        } elseif (is_string($this->saveResult) && !empty($this->saveResult)) {
            $saveResult = ['success' => false, 'message' => $this->saveResult];
        } else {
            $saveResult = ['success' => true];
        }
        $saveResult['class'] = $saveResult['success'] ? 'success' : 'error';

        if (empty($saveResult['message'])) {
            $saveResult['message'] = Yii::t('app', $this->options[$saveResult['class']]['message'],
                \yii\helpers\ArrayHelper::merge($model->getAttributes(), [
                    'scenario' => Inflector::camel2words(Inflector::id2camel($this->options['scenario']))
                ]));
        }

        return $saveResult;
    }

    public function getBehaviour($saveResult)
    {
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

    public function runBehaviour($rule, $saveResult, $model = null)
    {
        /** @var ActiveRecord $model */

        $format    = ArrayHelper::remove($rule, 'format', null);
        $behaviour = array_shift($rule);
        $params    = array_shift($rule);

        if ($format instanceof \Closure) {
            $data = call_user_func($format, $model, $saveResult);
        } else {
            $data = $model->getAttributes();
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
            $view   = array_shift($params);
            $params = array_shift($params);

            return $this->controller->render($view, $params);
        } elseif ($behaviour === 'renderJson') {
            /// ['renderJson', ['view', 'id' => 123], 'format' => function ($model, $success) {  return ['message' => $success ? '' : $model->getFirstError()]; ]
            return $this->controller->renderJson($data);
        } elseif ($behaviour === 'redirect') {
            if ($params instanceof \Closure) {
                $params = call_user_func($params, $model, $saveResult);
            }

            return $this->controller->redirect($params);
        } elseif ($behaviour === 'action') {
            /// ['view', ['someparam' => 1]]
            $action = $params[0];
            $params = $params[1];

            if ($params instanceof \Closure) {
                $params = call_user_func($params, $model, $saveResult);
            }

            if ($rule['setUrl']) {
                $rule['setUrl'] = call_user_func($rule['setUrl'], $model, $saveResult);
                $this->controller->redirect($rule['setUrl']);
            }

            return $this->controller->runAction($action, $params);
        } elseif ($behaviour === 'custom' && $params instanceof \Closure) { /// function ($action, $model) { return $model; }
            return call_user_func($params, $this, $model);
        } else {
            throw new InvalidCallException('Requested method action is not allowed!');
        }
    }

    public function getRequestType()
    {
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

    public function addFlash($model, $behaviour, $saveResult)
    {
        if (isset($behaviour['addFlash'])) {
            $addFlash = $behaviour['addFlash'];
        } elseif (isset($this->options['addFlash'])) {
            $addFlash = $this->options['addFlash'];
        } else {
            $addFlash = false;
        }

        if ($addFlash) {
            Yii::$app->session->addFlash($saveResult['class'], [
                'title' => $model->getPrimaryValue(),
                'text'  => $saveResult['message']
            ]);
        }
    }

    public function getRequestMethod()
    {
        return Yii::$app->request->method;
    }
}
