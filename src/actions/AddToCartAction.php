<?php

namespace hipanel\actions;

use Yii;
use yii\base\Action;

class AddToCartAction extends Action
{
    public $productClass;

    public function run()
    {
        $product = new $this->productClass;
        $cart = Yii::$app->cart;
        $request = Yii::$app->request;

        if ($product->load($request->get())) {
            if (!$cart->hasPosition($product->getId())) {
                $cart->put($product);
                Yii::$app->session->addFlash('success', Yii::t('app', 'Item is added to cart'));
            } else {
                Yii::$app->session->addFlash('warning', Yii::t('app', 'Item already exists in the cart'));
            }
            if ($request->isAjax) {
                Yii::$app->end();
            } else
                return $this->controller->redirect(Yii::$app->request->referrer);

        }
    }
}