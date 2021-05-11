<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use hipanel\models\Ref;

class FileInput extends \kartik\file\FileInput
{
    public function init()
    {
        $this->setAvailableFileTypes();
        parent::init();
    }

    protected function setAvailableFileTypes()
    {
        $refs = implode(', ', array_map(
            fn (Ref $model) => "\"{$model->name}\"",
            Ref::findCached('type,file')
        ));
        $this->options['data-allowed-file-extensions'] = "[{$refs}]";
    }
}
