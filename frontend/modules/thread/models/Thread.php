<?php

namespace app\modules\thread\models;

use Yii;
use yii\helpers\Markdown;

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
            // 'answer_message',
            'answers',


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
            'subject'          => Yii::t('app', 'Subject'),
            'message'          => Yii::t('app', 'Message'),
            'state'            => Yii::t('app', 'State'),
            'state_label'      => Yii::t('app', 'State_label'),
            'author_id'        => Yii::t('app', 'Author'),
            'responsible_id'   => Yii::t('app', 'Responsible'),
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

    public static function regexConfig ($target) {
        $config = [
            'tickets'   => [
                '/\#\d{6,9}(\#answer-\d{6,7})?\b/',
            ],
            'servers'   => [
                '/\b[A-Z]*DS\d{3,9}[A-Za-z0-9-]{0,6}\b/',
            ],
        ];
        return $config[$target];
    }

    public static function prepareLinks ($text) {
        $targets = ['thread', 'server'];
        $host = getenv("HTTP_HOST");
        foreach ($targets as $target) {
            foreach (self::regexConfig($target) as $pattern) {
                $matches = [];
                $changed = [];
                preg_match_all($pattern, $text, $matches);
                foreach ($matches[0] as $match) {
                    $number = $target=='tickets' ? substr($match, 1) : $match;
                    if ($changed[$number] && $changed[$number] == $match) continue;
                    $changed[$number] = $match;
                    $text = str_replace($match, "[[https://{$host}/panel/{$target}/details/{$number}|{$match}]]", $text);
                }
            }
        }
        return $text;
    }

    public static function parseMessage($message) {
        $message = str_replace(["\n\r", "\n\n", "\r\r", "\r\n"], "\n", $message);
        $message = self::prepareLinks($message);
        $message = Markdown::process($message);
        return $message;
    }

}