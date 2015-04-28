<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

use yii\base\InvalidConfigException;

class Gravatar extends \cebe\gravatar\Gravatar
{
    public $emailHash;

    private $_emailHash;

    /**
     * @return string
     * @throws InvalidConfigException
     */
    public function getEmailHash()
    {
        if ($this->_emailHash !== null) {
            return $this->_emailHash;
        } elseif ($this->email === null && $this->emailHash === null) {
            throw new InvalidConfigException('No email and emailHash specified for Gravatar image Widget.');
        }
        return $this->_emailHash = ($this->emailHash) ? $this->emailHash : md5(strtolower(trim($this->email)));
    }
}
