<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets\filePreview;

use yii\base\Exception;

/**
 * Class UnsupportedMimeTypeException.
 */
class UnsupportedMimeTypeException extends Exception
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Preview render of this file type is not supported';
    }
}
