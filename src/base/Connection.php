<?php

/*
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2016, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\base;

use Yii;

class Connection extends \hiqdev\hiart\Connection
{
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->errorChecker = [$this, 'checkError'];
    }

    /**
     * @param mixed $response The response
     * @return null|string
     *  - string: the error text
     *  - null: the response is not an error
     */
    public function checkError($response)
    {
        if ($response !== '0' && Err::is($response)) {
            $error = Err::get($response);
            if (empty($error)) {
                return 'unknown api error';
            } elseif ($error === 'invalid_token') {
                Yii::$app->user->logout();
                Yii::$app->response->refresh()->send();
                Yii::$app->end();
            } else {
                return $error;
            }
        }

        return null;
    }
}
