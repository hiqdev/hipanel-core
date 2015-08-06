<?php

namespace hipanel\actions;

use Closure;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * Class ViewAction
 */
class ViewAction extends Action
{
    /**
     * @var string view to render.
     */
    public $view = 'view';

    /**
     * @var string|integer ID of object to be viewed
     */
    protected $_id;

    /**
     * @var array|Closure additional data passed to view
     */
    public $data = [];

    /**
     * @var array additional data passed to model find method
     */
    public $findOptions = [];

    /**
     * @var array configuration array for new model creation
     */
    public $modelConfig = [];

    public function getId()
    {
        return $this->_id;
    }

    public function findModel($id)
    {
        return $this->controller->findModel(array_merge(['id' => $id], $this->findOptions), $this->modelConfig);
    }

    public function prepareData($id)
    {
        if ($this->data instanceof Closure) {
            return call_user_func($this->data, $this, $id);
        } else {
            return $this->data;
        }
    }

    public function run($id)
    {
        $this->_id = $id;
        $model     = $this->findModel($id);
        $this->collection->set($model);

        return $this->controller->render('view', ArrayHelper::merge(['model' => $model], $this->prepareData($id)));
    }
}
