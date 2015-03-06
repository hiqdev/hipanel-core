<?php
/**
 * Created by PhpStorm.
 * User: tofid
 * Date: 05.03.15
 * Time: 14:38
 */

namespace common\models;

use yii\base\Model;

class Layout extends Model
{
    /**
     * Can have values: fixed|layout-boxed
     * @var string
     */
    public $layoutType;

    /**
     * Can have values:
     *      skin-blue
     *      skin-yellow
     *      skin-purple
     *      skin-green
     *      skin-red
     *      skin-black
     * @var string
     */
    public $skin;

    /**
     * Can have value: table-condensed, or blank
     * @var bool
     */
    public $tableCondensed;
}