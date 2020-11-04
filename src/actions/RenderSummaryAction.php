<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\actions;

use Closure;

class RenderSummaryAction extends RenderAction
{
    public Closure $summary;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return call_user_func($this->summary, $this);
    }
}
