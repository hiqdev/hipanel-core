<?php
declare(strict_types=1);

namespace hipanel\grid;

use DateTime;
use hipanel\grid\BoxedGridView;
use hipanel\grid\DataColumn;
use hipanel\grid\MainColumn;
use hipanel\grid\RefColumn;
use hipanel\grid\XEditableColumn;
use hipanel\helpers\Url;
use hipanel\modules\client\helpers\ClientProfitColumns;
use hipanel\modules\client\menus\ClientActionsMenu;
use hipanel\modules\client\models\Client;
use hipanel\modules\client\widgets\ClientState;
use hipanel\modules\client\widgets\ClientType;
use hipanel\modules\finance\controllers\BillController;
use hipanel\modules\finance\grid\BalanceColumn;
use hipanel\modules\finance\grid\CreditColumn;
use hipanel\modules\finance\widgets\ColoredBalance;
use hipanel\modules\stock\helpers\ProfitColumns;
use hipanel\widgets\ArraySpoiler;
use hiqdev\yii2\menus\grid\MenuColumn;
use Yii;
use yii\helpers\Html;
use yii\helpers\Inflector;

class RefGridView extends BoxedGridView
{
    public function columns()
    {
        return array_merge(parent::columns(), [
            'name' => [
                'attribute' => 'name',
                'filter' => false,
                'contentOptions' => ['class' => 'text-nowrap'],
            ],
            'label' => [
                'attribute' => 'label',
                'filter' => false,
                'contentOptions' => ['class' => 'text-nowrap'],
            ],
        ]);
    }
}
