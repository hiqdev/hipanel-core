<?php
/**
 * @link    http://hiqdev.com/hipanel
 * @license http://hiqdev.com/hipanel/license
 * @copyright Copyright (c) 2015 HiQDev
 */
namespace hipanel\widgets;

use yii\base\InvalidValueException;
use yii\base\Widget;
use yii\data\Sort;


/**
 * This widget id rendered toggleable, contextual menu for displaying lists of sort links
 *
 * Class GridViewSortTool
 * @package hipanel\widgets
 * @author Tafid <andreyklochok@gmail.com>
 */
class GridViewSortTool extends Widget
{
    /**
     * @var string
     */
    public $containerClass = 'dropdown';

    /**
     * @var string
     */
    public $buttonClass = 'btn btn-default btn-xs dropdown-toggle';

    /**
     * @var array
     */
    public $sortNames = [];

    /**
     * @var array
     */
    public $linkOptions = [];

    /**
     * @var Sort
     */
    public $sort;

    public function init()
    {
        parent::init();
        if (!($this->sort instanceof Sort))
            throw new InvalidValueException('-sort- property must be an instance of \yii\data\Sort');
        if (empty($this->sortNames))
            throw new InvalidValueException('Sort names array can not be empty');
    }

    /**
     * @return string
     */
    public function run()
    {
        return $this->render('GridViewSortTool', [
            'id' => $this->id,
            'linkOptions' => $this->linkOptions,
            'sort' => $this->sort,
            'sortNames' => $this->sortNames,
            'containerClass' => $this->containerClass,
            'buttonClass' => $this->buttonClass,
        ]);
    }
}