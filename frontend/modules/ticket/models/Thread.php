<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace frontend\modules\ticket\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Markdown;

class Thread extends \hiqdev\hiar\ActiveRecord
{

    public $time_from;
    public $time_till;
    public $search_form;
    public $answer_spent;

    public function behaviors() {
        return [
            [
                'class' => 'common\behaviors\File',
                'attribute' => 'file',
                'savedAttribute' => 'file_ids',
                'scenarios' => ['insert', 'answer'],
            ]
        ];
    }

    public function attributes() {
        return [
            'id',
            'subject',
            'state',
            'state_label',
            'email',
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
            'spent_hours',
            'answer_count',
            'status',
            'reply_time',
            'create_time',
            'a_reply_time',
            'elapsed',
            'topics',
            'topic',
            'watchers',
            'watcher',
            'add_tag_ids',
            'file_ids',
            'file',
            // $with_messages_fields
            'message', // 'answer_message',
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
    public function rules() {
        return [
            [['subject', 'message'], 'required', 'on' => ['insert']],
            [['message'], 'required', 'on' => ['answer']],
            [
                [
                    'topics',
                    'state',
                    'priority',
                    'responsible_id',
                    'recipient_id',
                    'watchers',
                    'spent',
                    'spent_hours',
                    'file_ids'
                ],
                'safe',
                'on' => 'insert'
            ],
            [
                [
                    'topics',
                    'state',
                    'priority',
                    'responsible_id',
                    'recipient_id',
                    'watchers',
//                    'spent',
//                    'spent_hours',
                    'is_private',
                    'file_ids',
                    'answer_spent',
                ],
                'safe',
                'on' => 'answer'
            ],
            [['search_form'], 'safe'],
            [['file'], 'file', 'maxFiles' => 5],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'subject' => Yii::t('app', 'Subject'),
            'message' => Yii::t('app', 'Message'),
            'state' => Yii::t('app', 'State'),
            'state_label' => Yii::t('app', 'State'),
            'author_id' => Yii::t('app', 'Author'),
            'responsible_id' => Yii::t('app', 'Assignee'),
            'author' => Yii::t('app', 'Author'),
            'author_seller' => Yii::t('app', 'Seller'),
            'recipient_id' => Yii::t('app', 'Recipient'),
            'recipient' => Yii::t('app', 'Recipient'),
            'recipient_seller' => Yii::t('app', 'recipient_seller'),
            'replier_id' => Yii::t('app', 'replier_id'),
            'replier' => Yii::t('app', 'Replier'),
            'replier_seller' => Yii::t('app', 'replier_seller'),
            'replier_name' => Yii::t('app', 'replier_name'),
            'responsible' => Yii::t('app', 'Responsible'),
            'priority' => Yii::t('app', 'Priority'),
            'priority_label' => Yii::t('app', 'Priority'),
            'spent' => Yii::t('app', 'Spent time'),
            'spent_hours' => Yii::t('app', 'Spent hours'),
            'answer_count' => Yii::t('app', 'Answer count'),
            'status' => Yii::t('app', 'Status'),
            'reply_time' => Yii::t('app', 'reply_time'),
            'create_time' => Yii::t('app', 'Created'),
            'a_reply_time' => Yii::t('app', 'a_reply_time'),
            'elapsed' => Yii::t('app', 'elapsed'),
            'topic' => Yii::t('app', 'Topic'),
            'topics' => Yii::t('app', 'Topic'),
            'watchers' => Yii::t('app', 'Watchers'),
            'watcher' => Yii::t('app', 'Watchers'),
            'add_tag_ids' => Yii::t('app', 'add_tag_ids'),
            'file_ids' => Yii::t('app', 'file_ids'),
            'file' => Yii::t('app', 'Files'),
        ];
    }

    public function getThreadUrl() {
        return ['/ticket/ticket/view', 'id' => $this->id];
    }

    public function getThreadViewTitle() {
        return '#' . $this->id . ' ' . Html::encode($this->subject);
    }

    public static function regexConfig($target) {
        $config = [
            'ticket' => ['/\#\d{6,9}(\#answer-\d{6,7})?\b/',],
            'server' => ['/\b[A-Z]*DS\d{3,9}[A-Za-z0-9-]{0,6}\b/',],
        ];
        return $config[$target];
    }

    public static function prepareLinks($text) {
        $targets = ['ticket', 'server'];
        $host = getenv("HTTP_HOST");
        foreach ($targets as $target) {
            foreach (self::regexConfig($target) as $pattern) {
                $matches = [];
                $changed = [];
                preg_match_all($pattern, $text, $matches);
                foreach ($matches[0] as $match) {
                    $number = $target == 'tickets' ? substr($match, 1) : $match;
                    if ($changed[$number] && $changed[$number] == $match) continue;
                    $changed[$number] = $match;
                    $text = str_replace($match, "[[https://{$host}/panel/{$target}/details/{$number}|{$match}]]", $text);
                }
            }
        }
        return $text;
    }

    public static function parseMessage($message) {
        $message = Html::encode($message); // prevent xss
        $message = str_replace(["\n\r", "\n\n", "\r\r", "\r\n"], "\n", $message);
        // $message = self::prepareLinks($message);
        $message = Markdown::process($message);
        return $message;
    }

    public function beforeSave($insert) {
        if (!parent::beforeSave($insert)) return false;
        // spent time handle
        $this->prepareSpentTime();
        $this->prepareTopic();

        return true;
    }

    public function prepareSpentTime() {
        list($this->spent_hours, $this->spent) = explode(":", $this->isNewRecord ? $this->spent : $this->answer_spent, 2);
    }

    public function prepareTopic() {
        $this->topics = implode(',', $this->topics);
    }

    public function afterFind() {
        // if (is_array($this->topics)) $this->topics = array_keys($this->topics);
        if (is_array($this->watchers)) $this->watchers = array_keys($this->watchers);

        parent::afterFind();
    }

    public function scenarioCommands() {
        return [
            'insert' => 'create'
        ];
    }
}
