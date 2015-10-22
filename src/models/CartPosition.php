<?php

namespace hipanel\models;

use yii\base\Object;
use yz\shoppingcart\CartPositionInterface;
use yz\shoppingcart\CartPositionTrait;

class CartPosition extends Object implements CartPositionInterface
{
    use CartPositionTrait;

    public $model;

    private $_id;

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

}