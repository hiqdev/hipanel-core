<?php declare(strict_types=1);

namespace hipanel\actions;

use Closure;
use Exception;
use Yii;

class ProgressAction extends Action
{
    public ?Closure $onProgress = null;
    public ?Closure $onGettingId = null;
    public bool $needToTerminate = false;
    /** How many seconds in a row an unchanged state is polled before the stream is closed. */
    public int $maxIdleSeconds = self::INIT_TRIES;
    private int $triesLeft;
    private ?string $lastHash = null;
    private const int INIT_TRIES = 60;

    public function run()
    {
        $this->triesLeft = $this->maxIdleSeconds;
        $charset = Yii::$app->charset;
        header("Content-Type: text/event-stream; charset=$charset");
        header('Cache-Control: no-store');
        header('Connection: keep-alive');
        header('X-Accel-Buffering: no');
        $id = $this->onGettingId ? call_user_func($this->onGettingId, $this) : Yii::$app->user->id;
        do {
            try {
                $data = call_user_func($this->onProgress, $this);
                $data = str_replace(["\n", "\r"], '', $data);
            } catch (Exception $exception) {
                Yii::error(implode(PHP_EOL, [__CLASS__, $exception->getMessage()]));
                break;
            }
            $this->printMessage($id, $data);
            @ob_flush();
            flush();
            if (connection_aborted()) {
                break;
            }
            sleep(1);
        } while (!$this->needToTerminate && $this->isLimitHasNotBeenReachedYet($id, $data));
        die();
    }

    private function isLimitHasNotBeenReachedYet(mixed $id, string $data): bool
    {
        $hash = hash('sha256', implode("", [$id, $data]));
        if ($hash !== $this->lastHash) {
            $this->lastHash = $hash;
            $this->triesLeft = $this->maxIdleSeconds;
        }
        $this->triesLeft--;

        return $this->triesLeft > 0;
    }

    private function printMessage(mixed $id, string $data): void
    {
        // the order of the array elements is IMPORTANT (id after data)
        echo implode(PHP_EOL, [
            "data: $data",
            "id: $id",
            "retry: 300000", // 5 min
        ]);
        echo str_repeat(PHP_EOL, 2);
    }
}
