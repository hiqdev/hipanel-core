<?php
namespace frontend\components;

use \yii\base\DynamicModel;

class Himodels {
    public static function ticketModel() {
        $model = new DynamicModel([
            'subject',
            'message',
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
        ]);
        $model->addRule(['subject','message'], 'required')
              ->addRule('subject', 'string',['max'=>32]);

        return $model;
    }

    public static function Tickettags() {
        $model = DynamicModel([
            'id',
            'client_id',
            'client',
            'name',
            'type',
            'type_label',
            'descr',
            'is_public',
            'state_label',
            'state',
            'fullname',
            'color',
            'bgcolor',
        ]);
        return $model;
    }
}