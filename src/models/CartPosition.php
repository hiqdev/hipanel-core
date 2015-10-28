<?php

namespace hipanel\models;

use hiqdev\hiart\ActiveRecord;
use Yii;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;

class CartPosition extends ActiveRecord implements CartPositionInterface
{
    use CartPositionTrait;

    protected $_model;

    protected $_id;

    public $model_id;

    public $name;

    public $description;

    public function rules()
    {
        return [
            [['model_id'], 'integer', 'min' => 1],
            [['name', 'description'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'model_id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    public function attributes() {
        return [
            'model_id',
            'quantity'
        ];
    }

    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @return integer
     */
    public function getPrice()
    {
//        return $this->model->getPrice();
        return 10;
    }

    /**
     * @param bool $withDiscount
     * @return integer
     */
    public function getCost($withDiscount = true)
    {
//        return $this->model->getCost($withDiscount);
        return $this->price * $this->quantity;
    }

    /**
     * @return string
     */
    public function getId()
    {
        if ($this->_id === null) {
            $this->_id = mt_rand();
        }

        return $this->_id;
    }

    /**
     * @param int $quantity
     */
    public function setQuantity($quantity)
    {
        return $this->quantity = $quantity;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getQuantityOptions()
    {
        return [];
    }
}