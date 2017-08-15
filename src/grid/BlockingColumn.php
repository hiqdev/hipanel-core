<?php

namespace hipanel\grid;

use Exception;
use hipanel\widgets\ClientSellerLink;
use Yii;

class BlockingColumn extends \hiqdev\higrid\DataColumn
{
    public function init()
    {
        try {
            $this->visible = reset($this->grid->dataProvider->getModels())->blocking->reason;
        } catch (Exception $e) {

        } finally {
            parent::init();
        }
    }

    protected
    function renderDataCellContent($model, $key, $index)
    {
        $html = '';
        if ($model->blocking && $model->blocking->reason) {
            $reasonLabel = Yii::t('hipanel:block-reasons', $model->blocking->reason_label);
            $time = Yii::$app->formatter->asDate($model->blocking->time);
            $clientSellerLink = $model->blocking->client_id ? ClientSellerLink::widget(['model' => $model->blocking]) : '';
            $html = <<<HTML
                <div>
                    <small class="pull-right text-warning">{$time}</small>
                    <strong>{$reasonLabel}</strong>
                    <div>{$model->blocking->comment}</div>
                    <small class="text-muted">{$clientSellerLink}</small>
                </div>
HTML;
        }

        return $html;
    }
}
