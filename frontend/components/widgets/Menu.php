<?php
namespace frontend\components\widgets;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
/**
 * Class Menu
 * @package vova07\themes\admin\widgets
 * Theme menu widget.
 */
class Menu extends \yii\widgets\Menu
{

    /**
     * @inheritdoc
     */
    public $linkTemplate = '<a href="{url}">{icon} {label} {arrow}</a>';
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


    public function run() {
        $this->items = $this->menuItems();
        parent::run();
    }

    /**
     * @inheritdoc
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'options', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = [];
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            $menu = $this->renderItem($item);
            if (!empty($item['items'])) {
                $class[] = $this->treeClass;
                $menu .= strtr($this->submenuTemplate, [
                    '{items}' => $this->renderItems($item['items']),
                ]);
            }
            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }
            $lines[] = Html::tag($tag, $menu, $options);
        }
        return implode("\n", $lines);
    }
    /**
     * @inheritdoc
     */
    protected function renderItem($item)
    {
        if (isset($item['url'])) {
            $template = ArrayHelper::getValue($item, 'template', $this->linkTemplate);
            $replace = !empty($item['icon']) ? [
                '{url}' => Url::to($item['url']),
                '{label}' => $item['label'],
                '{icon}' => '<i class="fa ' . $item['icon'] . '"></i> ',
                '{arrow}' => !empty($item['items']) ? '<i class="fa pull-right fa-angle-left"></i>' : ''
            ] : [
                '{url}' => Url::to($item['url']),
                '{label}' => $item['label'],
                '{icon}' => $item['icon'] !== false ? '<i class="fa fa-angle-double-right"></i>' : '',
                '{arrow}' => !empty($item['items']) ? '<i class="fa pull-right fa-angle-left"></i>' : ''
            ];
            return strtr($template, $replace);
        } else {
            $template = ArrayHelper::getValue($item, 'template', $this->labelTemplate);
            $replace = !empty($item['icon']) ? [
                '{label}' => $item['label'],
                '{icon}' => '<i class="fa ' . $item['icon'] . '"></i> ',
                '{arrow}' => !empty($item['items']) ? '<i class="fa pull-right fa-angle-left"></i>' : ''
            ] : [
                '{label}' => $item['label'],
                '{icon}' => $item['icon'] !== false ? '<i class="fa fa-angle-double-right"></i>' : '',
                '{arrow}' => !empty($item['items']) ? '<i class="fa pull-right fa-angle-left"></i>' : ''
            ];
            return strtr($template, $replace);
        }
    }
    /**
     * @inheritdoc
     */
    protected function normalizeItems($items, &$active)
    {
        foreach ($items as $i => $item) {
            if (isset($item['visible']) && !$item['visible']) {
                unset($items[$i]);
                continue;
            }
            if (!isset($item['label'])) {
                $item['label'] = '';
            }
            $encodeLabel = isset($item['encode']) ? $item['encode'] : $this->encodeLabels;
            $items[$i]['label'] = $encodeLabel ? Html::encode($item['label']) : $item['label'];
            $items[$i]['icon'] = isset($item['icon']) ? $item['icon'] : '';
            $hasActiveChild = false;
            if (isset($item['items'])) {
                $items[$i]['items'] = $this->normalizeItems($item['items'], $hasActiveChild);
                if (empty($items[$i]['items']) && $this->hideEmptyItems) {
                    unset($items[$i]['items']);
                    if (!isset($item['url'])) {
                        unset($items[$i]);
                        continue;
                    }
                }
            }
            if (!isset($item['active'])) {
                if ($this->activateParents && $hasActiveChild || $this->activateItems && $this->isItemActive($item)) {
                    $active = $items[$i]['active'] = true;
                } else {
                    $items[$i]['active'] = false;
                }
            } elseif ($item['active']) {
                $active = true;
            }
        }
        return array_values($items);
    }

    private function menuItems() {
        return [
            [
                'label' => Yii::t('app', 'MAIN NAVIGATION'),
                'options' => ['class' => 'header'],
            ],
            [
                'label' => 'Dashboard',
                'url' => ['/hipanel'],
                'icon' => 'fa-dashboard',
                'active' => Yii::$app->request->url === Yii::$app->homeUrl
            ],
            [
                'label' => Yii::t('app', 'Clients'),
                'url' => ['/client/client/index'],
                'icon' => 'fa-group',
                'items' => [
                    [
                        'label' => Yii::t('app', 'Clients list'),
                        'url' => ['/client/client/index'],
                        'icon' => 'fa-circle-o',
                    ],
                    [
                        'label' => Yii::t('app', 'Mailings'),
                        'url' => ['/client/mailing/index'],
                        'icon' => 'fa-circle-o',
                    ],
                    [
                        'label' => Yii::t('app', 'News and articles'),
                        'url' => ['/client/article/index'],
                        'icon' => 'fa-circle-o',
                    ],
                ]
            ],
            [
                'label' => Yii::t('app', 'Finance'),
                'url' => ['/finance/bill/index'],
                'icon' => 'fa-dollar',
                'visible' => true,
                'items' => [
                    [
                        'label' => Yii::t('app', 'Payments'),
                        'url' => ['/finance/bill/index'],
                        'icon' => 'fa-money',
                    ],
                    [
                        'label' => Yii::t('app', 'Recharge account'),
                        'url' => ['/finance/bill/deposit'],
                        'icon' => 'fa-credit-card',
                    ],
                    [
                        'label' => Yii::t('app', 'Tariffs'),
                        'url' => ['/finance/tariff/index'],
                        'icon' => 'fa-circle-o',
                    ],
                ]
            ],
            [
                'label' => Yii::t('app', 'Tickets'),
                'url' => ['/ticket/ticket/index'],
                'icon' => 'fa-ticket',
                'items' => [
                    [
                        'label' => Yii::t('app', 'Tickets'),
                        'url' => ['/ticket/ticket/index'],
                        'icon' => 'fa-ticket',
                    ],
                    [
                        'label' => Yii::t('app', 'Tickets settings'),
                        'url' => ['/ticket/ticket/settings'],
                        'icon' => 'fa-gears',
                    ],
                    [
                        'label' => Yii::t('app', 'Tickets statistic'),
                        'url' => ['/ticket/ticket/statistic'],
                        'icon' => 'fa-bar-chart',
                    ],
                ]
            ],
            [
                'label' => Yii::t('app', 'Domains'),
                'url' => ['/domains/default/index'],
                'icon' => 'fa-globe',
                'visible' => true,
                'items' => [
                    [
                        'label' => Yii::t('app', 'My domains'),
                        'url' => ['/domain/domain/index'],
                        'icon' => 'fa-circle-o',
                    ],
                    [
                        'label' => Yii::t('app', 'Name Servers'),
                        'url' => ['/domain/host/index'],
                        'icon' => 'fa-circle-o',
                    ],
                    [
                        'label' => Yii::t('app', 'Contacts'),
                        'url' => ['/client/contact/index'],
                        'icon' => 'fa-circle-o',
                    ],
                    [
                        'label' => Yii::t('app', 'SEO'),
                        'url' => ['/domain/domain/index'],
                        'icon' => 'fa-circle-o',
                    ],
                ]
            ],
            [
                'label' => Yii::t('app', 'Servers'),
                'url' => ['/server/server/index'],
                'icon' => 'fa-server',
                'visible' => true,
                'items' => [
                    [
                        'label' => Yii::t('app', 'Servers'),
                        'url' => ['/server/server/index'],
                    ],
                ]
            ],
            [
                'label' => Yii::t('app', 'Hosting'),
                'url' => '#',
                'icon' => 'fa-sitemap',
                'visible' => true,
                'items' => [
                    [
                        'label' => Yii::t('app', 'Accounts'),
                        'url' => ['/hosting/account/index'],
                        'icon' => 'fa-user',
                    ],
                    [
                        'label' => Yii::t('app', 'DataBases'),
                        'url' => ['/hosting/database/index'],
                        'icon' => 'fa-database',
                    ],
                ]
            ],
            [
                'label' => Yii::t('app', 'Access control'),
                'url' => '#',
                'icon' => 'fa-gavel',
                'visible' => false,
                'items' => [
                    [
                        'label' => Yii::t('app', 'Permissions'),
                        'url' => ['/rbac/permissions/index'],
                        'icon' => 'fa-circle-o',
                        //     'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewPermissions')
                    ],
                    [
                        'label' => Yii::t('app', 'Roles'),
                        'url' => ['/rbac/roles/index'],
                        'icon' => 'fa-circle-o',
                        //                        'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewRoles')
                    ],
                    [
                        'label' => Yii::t('app', 'Rules'),
                        'url' => ['/rbac/rules/index'],
                        'icon' => 'fa-circle-o',
                        //                        'visible' => Yii::$app->user->can('administrateRbac') || Yii::$app->user->can('BViewRules')
                    ]
                ]
            ],
        ];
    }
}
