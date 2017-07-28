<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\db\ActiveRecord;

class BulkOperation extends Widget
{
    /**
     * @var ActiveRecord
     */
    public $model;

    /**
     * @var ActiveRecord[]
     */
    public $models;

    /**
     * @var string
     */
    public $scenario;

    /**
     * @var array
     */
    public $hiddenInputs = [];

    /**
     * @var array
     */
    public $visibleInputs = [];

    /**
     * @var array
     */
    public $dropDownInputs = [];

    /**
     * @var string
     */
    public $headerTitle;

    /**
     * @var string
     */
    public $bodyWarning;

    /**
     * @var string
     */
    public $panelBody;

    /**
     * @var string
     */
    public $submitButton;
    public $submitButtonOptions;

    /**
     * @var string
     */
    public $affectedObjects;

    /**
     * @var string
     */
    public $formatterField = 'name';

    public function init()
    {
        parent::init();

        if ($this->model === null) {
            throw new InvalidConfigException('Please specify the "model" property.');
        }

        if ($this->models === null) {
            throw new InvalidConfigException('Please specify the "models" property.');
        }

        if ($this->scenario === null) {
            throw new InvalidConfigException('Please specify the "scenario" property.');
        }

        if ($this->affectedObjects === null) {
            throw new InvalidConfigException('Please specify the "affectedObjects" property.');
        }

        if ($this->hiddenInputs === null) {
            $this->hiddenInputs = [];
        }

        if ($this->dropDownInputs === null) {
            $this->dropDownInputs = [];
        }

        if ($this->visibleInputs === null) {
            $this->visibleInputs = [];
        }
    }

    public function run()
    {
        echo $this->render('bulk-operation', [
            'model' => $this->model,
            'models' => $this->models,
            'scenario' => $this->scenario,
            'formatterField' => $this->formatterField,
            'panelBody' => $this->panelBody,
            'bodyWarning' => $this->bodyWarning,
            'hiddenInputs' => $this->hiddenInputs,
            'visibleInputs' => $this->visibleInputs,
            'dropDownInputs' => $this->dropDownInputs,
            'submitButton' => $this->submitButton,
            'submitButtonOptions' => $this->submitButtonOptions,
            'affectedObjects' => $this->affectedObjects,
        ]);
    }
}
