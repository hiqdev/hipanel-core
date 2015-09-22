<?php

namespace hipanel\base;

class Response extends \yii\web\Response
{
    public function sendContent()
    {
        if ($this->stream === null) {
            $this->content = Lang::lang($this->content);
        }
        parent::sendContent();
    }
}
