<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use Exception;
use hipanel\widgets\ClientSellerLink;
use Yii;

class BlockingColumn extends \hiqdev\higrid\DataColumn
{
    public function init()
    {
        try {
            $models = $this->grid->dataProvider->getModels();
            $this->visible = reset($models)->blocking->reason;
        } catch (Exception $e) {
        } finally {
            parent::init();
        }
    }

    protected function renderDataCellContent($model, $key, $index)
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
