<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\behaviors;

use hipanel\components\UiOptionsStorage;
use hipanel\grid\RepresentationCollectionFinder;
use hipanel\models\IndexPageUiOptions;
use hiqdev\higrid\representations\RepresentationCollectionInterface;
use Yii;
use yii\base\Behavior;
use yii\helpers\Html;
use yii\web\Controller;

class UiOptionsBehavior extends Behavior
{
    /**
     * @var array
     */
    public $allowedRoutes = ['index', 'export'];

    /**
     * @var mixed
     */
    public $modelClass;

    /**
     * @var IndexPageUiOptions
     */
    private $_model;

    /**
     * @var RepresentationCollectionFinder
     */
    private $representationCollectionFinder;

    public function __construct(RepresentationCollectionFinder $representationCollectionFinder, array $config = [])
    {
        parent::__construct($config);
        $this->representationCollectionFinder = $representationCollectionFinder;
    }

    public function events()
    {
        return [Controller::EVENT_BEFORE_ACTION => 'ensureUiOptions'];
    }

    public function ensureUiOptions($event)
    {
        if (!$this->isRouteAllowed()) {
            return;
        }

        $options = [];
        $params = Yii::$app->request->get();
        $model = $this->getModel();
        $model->attributes = $this->getUiOptionsStorage()->get($this->getRoute());
        $model->availableRepresentations = $this->findRepresentations();
        if ($params) {
            foreach ($params as $key => $value) {
                if (in_array($key, array_keys($model->toArray()), true)) {
                    $options[$key] = $value;
                }
            }
            $model->attributes = $options;

            if ($model->validate()) {
                $this->getUiOptionsStorage()->set($this->getRoute(), $model->toArray());
            } else {
                $errors = json_encode($model->getErrors());
                Yii::warning('UiOptionsBehavior - IndexPageUiModel validation errors: ' . $errors);
            }
        }
    }

    protected function getModel()
    {
        if ($this->_model === null) {
            $this->_model = $this->findModel();
        }

        return $this->_model;
    }

    protected function findModel()
    {
        return $this->owner->indexPageUiOptionsModel;
    }

    protected function isRouteAllowed()
    {
        return in_array($this->owner->action->id, $this->allowedRoutes);
    }

    /**
     * @return UiOptionsStorage
     */
    protected function getUiOptionsStorage()
    {
        return Yii::$app->get('uiOptionsStorage');
    }

    /**
     * example: store/part/index
     *
     * @return string
     */
    protected function getRoute()
    {
        $request = Yii::$app->request;
        if ($this->isRouteAllowed() && $request->get('route', false)) {
            return Html::encode($request->get('route'));
        }

        return Yii::$app->request->pathInfo;
    }

    protected function findRepresentations()
    {
        return $this->representationCollectionFinder->findOrFallback()->getAll();
    }
}
