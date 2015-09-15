<?php

namespace hipanel\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;

/**
 * Class Menu
 * Themed menu widget.
 */
class Menu extends \hiqdev\menumanager\widgets\Menu
{
    /**
     * @inheritdoc
     */
    public $linkTemplate = '<a href="{url}">{icon} <span>{label}</span> {arrow}</a>';

    /**
     * @inheritdoc
     */
    public $submenuTemplate = "\n<ul class='treeview-menu'>\n{items}\n</ul>\n";

    /**
     * @var string Class that will be added for parents "li"
     */
    public $treeClass = 'treeview';

    /**
     * @inheritdoc
     */
    public $activateParents = true;

    public $defaultIcon = 'fa-angle-double-right';
}
