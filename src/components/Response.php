<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\components;

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
