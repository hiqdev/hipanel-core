<?php

namespace hipanel\base;

class Connection extends \hiqdev\hiart\Connection
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->errorChecker = function ($response) {
            return $this->checkError($response);
        };
    }

    /**
     * @param mixed $response The response
     * @return null|string
     *  - string: the error text
     *  - null: the response is not an error
     */
    static public function checkError($response)
    {
        if ($response !== '0' && Err::is($response)) {
            return Err::get($response) ?: 'unknown api error';
        }

        return null;
    }
}