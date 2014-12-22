<?php

namespace app\modules\thread\models;

use Yii;
use yii\helpers\Markdown;

class Thread extends \frontend\components\hiresource\ActiveRecord
{
    public $time_from;
    public $time_till;

    public function ticketState() {
        $labelClass = ($this->state=='closed') ? 'default' : 'success' ;
        return '<span class="label label-'.$labelClass.'">'.$this->state.'</span>';
    }

    public function ticketPriority() {
        $labelClass = ($this->priority=='medium') ? 'primary' : 'warning' ;
        return '<span class="label label-'.$labelClass.'">'.$this->priority.'</span>';
    }

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
            'priority_label',
            'spent',
            'answer_count',
            'status',
            'reply_time',
            'create_time',
            'a_reply_time',
            'elapsed',
            'topic',
            'watchers',
            'watcher',
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
            'state_label'      => Yii::t('app', 'State'),
            'author_id'        => Yii::t('app', 'Author'),
            'responsible_id'   => Yii::t('app', 'Responsible'),
            'author'           => Yii::t('app', 'Author'),
            'author_seller'    => Yii::t('app', 'Seller'),
            'recipient_id'     => Yii::t('app', 'recipient_id'),
            'recipient'        => Yii::t('app', 'Recipient'),
            'recipient_seller' => Yii::t('app', 'recipient_seller'),
            'replier_id'       => Yii::t('app', 'replier_id'),
            'replier'          => Yii::t('app', 'Replier'),
            'replier_seller'   => Yii::t('app', 'replier_seller'),
            'replier_name'     => Yii::t('app', 'replier_name'),
            'responsible'      => Yii::t('app', 'Responsible'),
            'priority'         => Yii::t('app', 'Priority'),
            'priority_label'   => Yii::t('app', 'Priority'),
            'spent'            => Yii::t('app', 'Spent time'),
            'answer_count'     => Yii::t('app', 'Answer count'),
            'status'           => Yii::t('app', 'Status'),
            'reply_time'       => Yii::t('app', 'reply_time'),
            'create_time'      => Yii::t('app', 'Created'),
            'a_reply_time'     => Yii::t('app', 'a_reply_time'),
            'elapsed'          => Yii::t('app', 'elapsed'),
            'topic'            => Yii::t('app', 'Topic'),
            'watchers'         => Yii::t('app', 'Watchers'),
            'watcher'          => Yii::t('app', 'Watchers'),
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