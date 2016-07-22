<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\grid;

use hipanel\widgets\XEditable;
use Yii;

/**
 * Class XEditableColumn.
 */
class XEditableColumn extends \hiqdev\xeditable\grid\XEditableColumn
{
    public function init()
    {
        parent::init();

        $this->widgetOptions['class'] = XEditable::class;
    }
}
