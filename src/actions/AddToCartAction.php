<?php

namespace hipanel\actions;

use hiqdev\hiart\Collection;
use Yii;
use yii\base\Action;

class AddToCartAction extends Action
{
    public $productClass;

    public $bulkLoad = false;

    public function run()
    {
        $data = null;

        $collection = new Collection([
            'model' => new $this->productClass
        ]);
        $cart = Yii::$app->cart;
        $request = Yii::$app->request;
//        $data = $request->isPost ? $request->post() : $request->get();

        if (!$this->bulkLoad) {
            $data = [Yii::$app->request->post() ?: Yii::$app->request->get()];
        }

        if ($collection->load($data) && $collection->validate()) {
            foreach ($collection->models as $position) {
                if (!$cart->hasPosition($position->getId())) {
                    $cart->put($position);
                    Yii::$app->session->addFlash('success', Yii::t('app', 'Item is added to cart'));
                } else {
                    Yii::$app->session->addFlash('warning', Yii::t('app', 'Item already exists in the cart'));
                }
            }
        } else {
            Yii::$app->session->addFlash('warning', Yii::t('app', 'Item does not exists'));
        }

        if ($request->isAjax) {
            Yii::$app->end();
        } else {
            return $this->controller->redirect(Yii::$app->request->referrer);
        }
    }
}