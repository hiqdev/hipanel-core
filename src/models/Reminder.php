<?php

namespace hipanel\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;
use Yii;

class Reminder extends Model
{
    use ModelTrait;

    public static $i18nDictionary = 'hipanel/reminder';

    const REMINDER_SCENARIO_CREATE = 'create';
    const REMINDER_SCENARIO_UPDATE = 'update';
    const REMINDER_SCENARIO_DELETE = 'delete';

    const REMINDER_TYPE_SITE = 'site';
    const REMINDER_TYPE_MAIL = 'mail';

    public static function reminderNextTimeOptions()
    {
        return [
            '+ 15 minutes' => Yii::t('hipanel/reminder', '15m'),
            '+ 30 minutes' => Yii::t('hipanel/reminder', '30m'),
            '+ 1 hour' => Yii::t('hipanel/reminder', '1h'),
            '+ 12 hour' => Yii::t('hipanel/reminder', '12h'),
            '+ 1 day' => Yii::t('hipanel/reminder', '1d'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'object_id', 'client_id', 'state_id', 'type_id'], 'integer'],
            [['class', 'periodicity', 'from_time', 'till_time', 'next_time'], 'string'],
            [['to_site'], 'boolean'],

            // Create
            [['object_id', 'type', 'periodicity', 'from_time', 'message'], 'required', 'on' => self::REMINDER_SCENARIO_CREATE],

            // Update
            [['id'], 'required', 'on' => 'update'],
            [['object_id', 'state_id', 'type_id'], 'integer', 'on' => self::REMINDER_SCENARIO_UPDATE],
            [['from_time', 'next_time', 'till_time'], 'string', 'on' => self::REMINDER_SCENARIO_UPDATE],

            // Delete
            [['id'], 'required', 'on' => self::REMINDER_SCENARIO_DELETE]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return $this->mergeAttributeLabels([
            'periodicity' => Yii::t('hipanel/reminder', 'Periodicity'),
            'from_time' => Yii::t('hipanel/reminder', 'When the recall?'),
            'next_time' => Yii::t('hipanel/reminder', 'Next remind'),
            'till_time' => Yii::t('hipanel/reminder', 'Remind till'),
            'message' => Yii::t('hipanel/reminder', 'Message'),
        ]);
    }

    public function getObjectName()
    {
        $result = '';
        if ($this->class) {
            switch ($this->class) {
                case 'thread':
                    $result = 'ticket';
                    break;
            }
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     * @return ReminderQuery
     */
    public static function find($options = [])
    {
        return new ReminderQuery(get_called_class(), [
            'options' => $options,
        ]);
    }
}
