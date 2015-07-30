<?php

namespace hipanel\actions;

use Yii;

/**
 * Class SmartUpdateAction
 */
class SmartUpdateAction extends SwitchAction
{
    public $_items = [
        'POST xeditable' => [
            'class' => 'hipanel\actions\XEditableAction',
        ],
    ];
}
