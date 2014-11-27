<?php

namespace app\modules\ticket\models;

use Yii;

class Ticket extends \yii\db\ActiveRecord
{
    public $subject;
    public $message;
    public $state;
    public $state_label;
    public $author_id;
    public $responsible_id;
    public $author;
    public $author_seller;
    public $recipient_id;
    public $recipient;
    public $recipient_seller;
    public $replier_id;
    public $replier;
    public $replier_seller;
    public $replier_name;
    public $responsible;
    public $priority;
    public $spent;
    public $answer_count;
    public $status;
    public $reply_time;
    public $create_time;
    public $a_reply_time;
    public $elapsed;
    public $topic;
    public $watchers;
    public $add_tag_ids;
    public $file_ids;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ticket';
    }

    public static function getDb()
    {
        return \Yii::$app->hipanel;  // use the "db2" application component
    }

    /**
     * @inheritdoc
     */
    public function rules () {
        return [
            [['subject', 'message'], 'required'],
            [
                [
                    'state',
                    'state_label',
                    'author_id',
                    'responsible_id',
                    'author',
                    'author_seller',
                    'recipient_id',
                    'recipient',
                    'recipient_seller',
                    'replier_id',
                    'replier',
                    'replier_seller',
                    'replier_name',
                    'responsible',
                    'priority',
                    'spent',
                    'answer_count',
                    'status',
                    'reply_time',
                    'create_time',
                    'a_reply_time',
                    'elapsed',
                    'topic',
                    'watchers',
                    'add_tag_ids',
                    'file_ids',
                ],
                'safe'
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels () {
        return [
            'id'               => Yii::t('app', 'ID'),
            'subject'          => Yii::t('app', 'subject'),
            'message'          => Yii::t('app', 'message'),
            'state'            => Yii::t('app', 'state'),
            'state_label'      => Yii::t('app', 'state_label'),
            'author_id'        => Yii::t('app', 'author_id'),
            'responsible_id'   => Yii::t('app', 'responsible_id'),
            'author'           => Yii::t('app', 'author'),
            'author_seller'    => Yii::t('app', 'author_seller'),
            'recipient_id'     => Yii::t('app', 'recipient_id'),
            'recipient'        => Yii::t('app', 'recipient'),
            'recipient_seller' => Yii::t('app', 'recipient_seller'),
            'replier_id'       => Yii::t('app', 'replier_id'),
            'replier'          => Yii::t('app', 'replier'),
            'replier_seller'   => Yii::t('app', 'replier_seller'),
            'replier_name'     => Yii::t('app', 'replier_name'),
            'responsible'      => Yii::t('app', 'responsible'),
            'priority'         => Yii::t('app', 'priority'),
            'spent'            => Yii::t('app', 'spent'),
            'answer_count'     => Yii::t('app', 'answer_count'),
            'status'           => Yii::t('app', 'status'),
            'reply_time'       => Yii::t('app', 'reply_time'),
            'create_time'      => Yii::t('app', 'create_time'),
            'a_reply_time'     => Yii::t('app', 'a_reply_time'),
            'elapsed'          => Yii::t('app', 'elapsed'),
            'topic'            => Yii::t('app', 'topic'),
            'watchers'         => Yii::t('app', 'watchers'),
            'add_tag_ids'      => Yii::t('app', 'add_tag_ids'),
            'file_ids'         => Yii::t('app', 'file_ids'),
        ];
    }
}