<?php
/**
 * HiPanel core package.
 *
 * @link      https://hipanel.com/
 * @package   hipanel-core
 * @license   BSD-3-Clause
 * @copyright Copyright (c) 2014-2017, HiQDev (http://hiqdev.com/)
 */

namespace hipanel\widgets;

use hipanel\models\Ref;
use hiqdev\combo\StaticCombo;

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

    /**
     * @var string Dictionary name for i18n module to translate refs
     */
    public $i18nDictionary;

    public $_hasId = true;

    public function init()
    {
        $this->data = $this->prepareData();

        parent::init();
    }

    public function prepareData()
    {
        $refs = Ref::getList($this->gtype, $this->i18nDictionary, $this->findOptions);
        return $refs;
    }
}
