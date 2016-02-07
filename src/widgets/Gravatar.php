<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

class Gravatar extends \cebe\gravatar\Gravatar
{
    public $defaultImage = 'identicon';

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
        if (!$this->_emailHash) {
            $this->_emailHash = self::hashEmail($this->email);
        }

        return $this->_emailHash;
    }

    public static function hashEmail($email)
    {
        $email = strtolower(trim($email));
        return strpos($email, '@') === false ? $email : md5($email);
    }

    public function run()
    {
        if (!$this->emailHash) {
            $this->defaultImage = '';
        }
        return parent::run();
    }
}
