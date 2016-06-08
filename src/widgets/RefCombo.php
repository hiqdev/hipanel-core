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

use hiqdev\combo\StaticCombo;
use hipanel\models\Ref;

/**
 * Class RefCombo widget.
 *
 * Usage:
 * RefCombo::widget([
 *      'attribute'   => 'state',
 *      'model'       => $searchModel,
 *      'gtype'       => 'state,domain',
 *      'findOptions' => [],
 * ]);
 */
class RefCombo extends StaticCombo
{
    /**
     * @var string
     */
    public $gtype;

    /**
     * @var array additional find options that will be passed to [[Ref]] model
     */
    public $findOptions = [];

    public $_hasId = true;

    public function getData()
    {
        $refs = Ref::getList($this->gtype, $this->findOptions);
        $res = [];
        foreach ($refs as $key => $value) {
            $res[] = ['id' => $key, 'text' => $value];
        }

        return $res;
    }
}
