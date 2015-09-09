<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */

namespace hipanel\widgets;

/**
 * {@inheritdoc}
 * @package hipanel\widgets
 */
class PNotify extends \hiqdev\pnotify\PNotify
{
    /**
     * {@inheritdoc}
     */
    public $clientOptions = [
        'hide' => true,
        'buttons' => [
            'sticker' => false
        ]
    ];

    public function init() {
        parent::init();
        if (!isset($this->clientOptions['styling'])) {
             $this->clientOptions['styling'] = 'bootstrap3';
        }
    }
}
