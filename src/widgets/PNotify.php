<?php
/**
 * HiPanel core package
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2019, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

/**
 * {@inheritdoc}
 */
class PNotify extends \hiqdev\pnotify\PNotify
{
    /**
     * {@inheritdoc}
     */
    public $clientOptions = [
        'hide' => true,
        'buttons' => [
            'sticker' => false,
        ],
    ];

    public function init()
    {
        parent::init();
        if (!isset($this->clientOptions['styling'])) {
            $this->clientOptions['styling'] = 'bootstrap3';
        }
    }
}
