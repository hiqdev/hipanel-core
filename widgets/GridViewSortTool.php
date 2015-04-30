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
 * This vidget id rendered toggleable, contextual menu for displaying lists of sort links
 *
 * Class GridViewSortTool
 * @package hipanel\widgets
 * @author Tafid <andreyklochok@gmail.com>
 */
class GridViewSortTool extends Widget
{
    public $containerClass = 'dropdown';

    public $buttonClass = 'btn btn-default btn-xs dropdown-toggle';

    public $sortNames = [];

    public $sort;

    public function init()
    {
        parent::init();
        if (!($this->sort instanceof \yii\data\Sort))
            throw new InvalidValueException('-sort- property must be an instance of \yii\data\Sort');
        if (empty($this->sortNames))
            throw new InvalidValueException('Sort names array can not be empty');
    }

    public function run()
    {
        return $this->render('GridViewSortTool', [
            'sort' => $this->sort,
            'sortNames' => $this->sortNames,
            'containerClass' => $this->containerClass,
            'buttonClass' => $this->buttonClass,
        ]);
    }
}