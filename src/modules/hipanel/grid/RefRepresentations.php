<?php

namespace hipanel\modules\hipanel\grid;

use hipanel\modules\client\helpers\ClientProfitColumns;
use hipanel\modules\stock\helpers\ProfitColumns;
use hiqdev\higrid\representations\RepresentationCollection;
use Yii;

/**
 * Class RefRepresentations
 * @package hipanel\modules\hipanel\grid
 *
 * IMPORTANT: this class is here because RepresentationCollectionFinder has hardcoded template for
 * representation classes.
 * TODO: add possibility for refactoring of representation template
 *
 * @see \hipanel\grid\RepresentationCollectionFinder
 */
class RefRepresentations extends RepresentationCollection
{
    protected function fillRepresentations()
    {
        $this->representations = [
            'common' => [
                'label' => Yii::t('hipanel', 'Common'),
                'columns' => ['name', 'label'],
            ],
        ];
    }
}
