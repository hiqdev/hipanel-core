<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace frontend\components\hiresource;

/**
 * Exception represents an exception that is caused by elasticsearch-related operations.
 *
 * @author Carsten Brandt <mail@cebe.cc>
 * @since 2.0
 */
class HiResException extends \yii\db\Exception
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'HiResource Database Exception';
    }
}