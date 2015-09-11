<?php

namespace hipanel\actions;

use Closure;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

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

    public function run($id = null)
    {
        $this->_id = $this->_id ?: $id ?: Yii::$app->request->get('id') ?: Yii::$app->request->post($this->collection->formName)['id'];

        $id = $this->_id;
        if (empty($id)) {
            throw new BadRequestHttpException('Id is missing');
        }

        $model    = $this->findModel($id);
        $this->collection->set($model);

        return $this->controller->render($this->view, array_merge(['model' => $model], $this->prepareData()));
    }
}
