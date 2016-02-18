<?php
/**
 * Created by PhpStorm.
 * User: silverfire
 * Date: 18.02.16
 * Time: 16:44
 */

namespace hipanel\widgets;

use Yii;

/**
 * Class XEditable
 * @package hipanel\widgets
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
