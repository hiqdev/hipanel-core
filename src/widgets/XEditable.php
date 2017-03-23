<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use Yii;

/**
 * Class XEditable.
 */
class XEditable extends \hiqdev\xeditable\widgets\XEditable
{
    public function init()
    {
        parent::init();
        if (!isset($this->pluginOptions['emptytext'])) {
            $this->pluginOptions['emptytext'] = Yii::t('hipanel', 'Empty');
        }
    }
}
