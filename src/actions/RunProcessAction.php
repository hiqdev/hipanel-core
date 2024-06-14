<?php declare(strict_types=1);

namespace hipanel\actions;

use Closure;
use Exception;
use Yii;

class RunProcessAction extends Action
{
    public ?Closure $onRunProcess = null;

    public function run()
    {
        ignore_user_abort(true);
        ini_set('memory_limit', '10G');

        ob_start();

        header('Connection: close');
        header('Content-Length: ' . ob_get_length());
        ob_end_flush();
        @ob_flush();
        flush();
        session_write_close();
        fastcgi_finish_request(); // required for PHP-FPM (PHP > 5.3.3)

        try {
            call_user_func($this->onRunProcess, $this);
        } catch (Exception $exception) {
            Yii::error(implode(PHP_EOL, [__CLASS__, $exception->getMessage()]));
        }

        die(); // a must especially if set_time_limit=0 is used and the task ends
    }
}
