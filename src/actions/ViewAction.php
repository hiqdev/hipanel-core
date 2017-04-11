<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

use hiqdev\hiart\ActiveDataProvider;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class ViewAction.
 */
class ViewAction extends SearchAction
{
    /**
     * @var string view to render
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

    public function run($id = null)
    {
        if (isset($id)) {
            $this->setId($id);
        }

        return parent::run();
    }

    /** {@inheritdoc} */
    protected function getDefaultRules()
    {
        return array_merge([
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
                    },
                ],
            ],
        ], parent::getDefaultRules());
    }

    /**
     * Creates `ActiveDataProvider` with given options list, stores it to [[dataProvider]].
     * @throws BadRequestHttpException
     * @return ActiveDataProvider
     */
    public function getDataProvider()
    {
        if ($this->dataProvider === null) {
            $this->_id = $this->_id ?: Yii::$app->request->get('id');
            $this->dataProvider = $this->getSearchModel()->search([], $this->dataProviderOptions);
            $this->dataProvider->pagination = false;
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

    /** {@inheritdoc} */
    public function beforeSave()
    {
        parent::beforeSave();
        if (empty($this->dataProvider->query->where)) {
            throw new BadRequestHttpException('Filtering condition are required');
        }
    }

    /** {@inheritdoc} */
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
