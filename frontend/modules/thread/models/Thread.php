<?php

namespace app\modules\thread\models;

use Yii;

class Thread extends \frontend\components\hiresource\ActiveRecord
{
    public $time_from;
    public $time_till;

    public function attributes()
    {
        return [
            'id',
            'subject',
            'state',
            'state_label',
            'author',
            'author_id',
            'responsible_id',
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


            // $with_messages_fields
            'message',
            'answer_message',


            // $with_anonym_fields
            'is_private',
            'anonym_email',
            'anonym_seller',

//
//            'topic',
//            'watchers',
//            'add_tag_ids',
//            'file_ids',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules () {
        return [
            [[], 'required'],
            [
                [

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