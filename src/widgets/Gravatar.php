<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

class Gravatar extends \cebe\gravatar\Gravatar
{
    protected $_emailHash;

    public function setEmailHash($hash)
    {
        $this->_emailHash = $hash;
    }

    /**
     * @return string
     */
    public function getEmailHash()
    {
        return $this->_emailHash ?: self::hashEmail($this->email);
    }

    static public function hashEmail($email)
    {
        $email = strtolower(trim($email));
        return strpos($email, '@') === false ? $email : md5($email);
    }
}
