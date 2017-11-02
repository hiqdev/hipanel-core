<?php

namespace hipanel\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap\ButtonDropdown;

class IndexExport extends Widget
{
    public $representationCollection;

    public function run()
    {
        return ButtonDropdown::widget([
            'label' => '<i class="fa fa-share-square-o"></i>&nbsp;' . Yii::t('hipanel', 'Export'),
            'encodeLabel' => false,
            'options' => ['class' => 'btn-default btn-sm'],
            'dropdown' => [
                'items' => $this->getItems(),
            ],
        ]);
    }

    protected function getItems()
    {
        return [
            [
                'url' => ['export', 'format' => 'csv'],
                'label' => '<i class="fa fa-file-code-o"></i>' . Yii::t('hipanel', 'CSV'),
                'encode' => false,
            ],
            [
                'url' => ['export', 'format' => 'tsv'],
                'label' => '<i class="fa fa-file-code-o"></i>' . Yii::t('hipanel', 'TSV'),
                'encode' => false,
            ],
        ];
    }
}
