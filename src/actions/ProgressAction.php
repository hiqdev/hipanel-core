<?php

declare(strict_types=1);

namespace hipanel\actions;

use Closure;
use Exception;
use Yii;

class ProgressAction extends Action
{
    public ?Closure $onProgress = null;
    public ?Closure $onGettingId = null;

    public function run()
    {
        $charset = Yii::$app->charset;
        header("Content-Type: text/event-stream; charset=$charset");
        header('Cache-Control: no-cache');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');
        $id = $this->onGettingId ? call_user_func($this->onGettingId, $this) : Yii::$app->user->id;
        while (true) {
            try {
                $data = call_user_func($this->onProgress, $this);
            } catch (Exception $exception) {
                Yii::error(implode(PHP_EOL, [__CLASS__, $exception->getMessage()]));
                break;
            }
            // the order of the array elements is IMPORTANT (id after data)
            echo implode(PHP_EOL, [
                "data: " . str_replace(["\n", "\r"], '', $data),
                "id: $id",
            ]);
            echo str_repeat(PHP_EOL, 2);
            sleep(1);
            @ob_flush();
            flush();
        }
        Yii::$app->end();
    }
}
