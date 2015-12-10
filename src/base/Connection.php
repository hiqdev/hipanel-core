<?php

namespace hipanel\base;

class Connection extends \hiqdev\hiart\Connection {
    public $errorChecker = ['hipanel\base\Connection', 'checkError'];

    static public function checkError($response)
    {
        if ($response !== '0' && Err::is($response)) {
            return Err::get($response) ?: 'unknown api error';
        }

        return null;
    }
};
