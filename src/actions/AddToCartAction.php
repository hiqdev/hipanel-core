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
        $request = Yii::$app->request;

        if ($product->load($request->get())) {
            Yii::$app->cart->put($product);
            Yii::$app->session->addFlash('success', Yii::t('app', 'Domain is added to cart'));
            if ($request->isAjax) {
                Yii::$app->end();
            } else
                return $this->controller->redirect(Yii::$app->request->referrer);

        }
    }
}