<?php

namespace hipanel\actions;

use Closure;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class ViewAction
 */
class ViewAction extends SearchAction
{
    /**
     * @var string view to render.
     */
    public $view = 'view';

    /**
     * @var mixed ID of object to be viewed. Defaults to `$_GET['id']`
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

    public function run($id = null) {
        if (isset($id)) {
            $this->setId($id);
        }

        return parent::run();
    }

    public function init()
    {
        $this->addItems([
            'html | pjax' => [
                'save' => true,
                'flash' => false,
                'success' => [
                    'class' => RenderAction::class,
                    'view' => $this->view,
                    'data' => function () {
                        return $this->prepareData();
                    },
                    'params' => function () {
                        return [
                            'models' => $this->collection->models,
                            'model' => $this->collection->first,
                        ];
                    }
                ]
            ]
        ]);

        parent::init();
    }

    /**
     * Creates `ActiveDataProvider` with given options list, stores it to [[dataProvider]]
     * @return ActiveDataProvider
     * @throws BadRequestHttpException
     */
    public function getDataProvider()
    {
        if ($this->dataProvider === null) {
            $this->_id = $this->_id ?: Yii::$app->request->get('id');
            $this->dataProvider = $this->getSearchModel()->search([], $this->dataProviderOptions);
            $this->dataProvider->query->andFilterWhere(
                is_array($this->_id) ? ['in', 'id', $this->_id] : ['eq', 'id', $this->_id]
            );
            if (empty($this->dataProvider->query->where)) {
                throw new BadRequestHttpException('ID is missing');
            }

            $this->dataProvider->query->andFilterWhere($this->findOptions);
        }

        return $this->dataProvider;
    }

    public function beforeSave()
    {
        parent::beforeSave();
        if (empty($this->dataProvider->query->where)) {
            throw new BadRequestHttpException('Filtering condition are required');
        }
    }

    public function afterPerform()
    {
        if ($this->collection->count() === 0) {
            throw new NotFoundHttpException('Object not found');
        }
        parent::afterPerform();
    }

    public function getId()
    {
        return $this->_id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->_id = $id;
    }
}
