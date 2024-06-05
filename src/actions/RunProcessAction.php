<?php

declare(strict_types=1);

namespace hipanel\actions;

use Closure;
use Exception;
use Yii;

class RunProcessAction extends Action
{
    public ?Closure $onRunProcess = null;
    public ?Closure $onGettingProcessId = null;

    public function run()
    {
        $id = call_user_func($this->onGettingProcessId, $this);
        ignore_user_abort(true);
        ini_set('memory_limit', '10G');
//        ob_start();

//        $this->controller->response->getHeaders()->add('X-PJAX-URLLLLLLLLLL', 'XXXXXXXXXXXXXXXXXXXXXXXXx');
//        $this->controller->response->getHeaders()->add('Connection', 'close');
//        $this->controller->response->getHeaders()->add('Content-Length', ob_get_length());
//        $this->controller->response->send();
        $this->controller->asJson(['jobId' => $id]);


//        header('Connection: close');
//        header('Content-Length: ' . ob_get_length());
//        @ob_end_flush();
//        @ob_flush();
//        flush();
//        session_write_close();
//        fastcgi_finish_request(); // required for PHP-FPM (PHP > 5.3.3)

        try {
            call_user_func($this->onRunProcess, $this);
        } catch (Exception $exception) {
            Yii::error(implode(PHP_EOL, [__CLASS__, $exception->getMessage()]));
        }
    }
}
