<?php

namespace hipanel\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use hipanel\modules\server\models\Server;
use hiqdev\hiart\ActiveQuery;
use Yii;
use yii\db\QueryInterface;

class Resource extends Model
{
    use ModelTrait;

    public static function tableName(): string
    {
        return 'use';
    }

    public function rules()
    {
        return [
            [['id', 'object_id', 'type_id'], 'integer'],
            [['last', 'total'], 'number'],
            [['type', 'aggregation'], 'string'],
            [['time_from', 'time_till', 'date'], 'datetime', 'format' => 'php:Y-m-d'],
        ];
    }

    public function getServer(): ActiveQuery
    {
        return $this->hasOne(Server::class, ['object_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     * @return QueryInterface
     */
    public static function find(array $options = []): QueryInterface
    {
        return new ResourceQuery(get_called_class(), [
            'options' => $options,
        ]);
    }

    public function getAmount()
    {
        if (in_array($this->type, $this->getBandwidthTypes(), true)) {
            return $this->last;
        }
        if (in_array($this->type, $this->getTrafficTypes(), true)) {
            return $this->total;
        }

        return $this->total;
    }

    public function getDisplayAmount()
    {
        if (in_array($this->type, $this->getBandwidthTypes(), true)) {
            return round($this->getAmount() / (10 ** 6), 2);
        }
        if (in_array($this->type, $this->getTrafficTypes(), true)) {
            return round($this->getAmount() / (10 ** 9), 2);
        }

        return $this->getAmount();
    }

    public function getDisplayDate(): string
    {
        if ($this->aggregation === 'month') {
            return Yii::$app->formatter->asDate(strtotime($this->date), 'LLL y');
        }
        if ($this->aggregation === 'week') {
            return Yii::$app->formatter->asDate(strtotime($this->date), 'dd LLL y');
        }
        if ($this->aggregation === 'day') {
            return Yii::$app->formatter->asDate(strtotime($this->date), 'dd LLL y');
        }

        return Yii::$app->formatter->asDate(strtotime($this->date));
    }

    private function getTrafficTypes(): array
    {
        return ['server_traf_in', 'server_traf_max', 'server_traf'];
    }

    private function getBandwidthTypes(): array
    {
        return ['server_traf95_in', 'server_traf95_max', 'server_traf95'];
    }
}