<?php

namespace hipanel\log;

use Yii;
use yii\helpers\Url;

/**
 * Class EmailTarget
 * @package hipanel\log
 */
class EmailTarget extends \yii\log\EmailTarget
{
    /**
     * Sends log messages to specified email addresses.
     */
    public function export()
    {
        // moved initialization of subject here because of the following issue
        // https://github.com/yiisoft/yii2/issues/1446
        if (empty($this->message['subject'])) {
            $this->message['subject'] = 'Application Log';
        }
        $messages = array_map([$this, 'formatMessage'], $this->messages);
        $body = '';
        if (Yii::$app->hasModule('debug')) {
            $body .= 'See debug log ' . Url::to(['/debug/default/view', 'panel' => 'log', 'tag' => Yii::$app->getModule('debug')->logTarget->tag]) . "\n\n";
        }
        $body .= implode("\n", $messages);
        $this->composeMessage($body)->send($this->mailer);
    }
}
