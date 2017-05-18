<?php

use yii\helpers\Html;

/* @var array $items */
/* @var string $language */

?>
<?php if (count($items) > 1) : ?>
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <?= strtoupper(substr($language, 0, 2)) ?>
    </a>
    <ul class="dropdown-menu">
        <?php if (is_array($items)) : ?>
            <li>
                <ul class="lang-menu">
                    <?php foreach ($items as $code => $item): ?>
                        <li>
                            <?= Html::a(
                                $item['label'], $item['url'],
                                ['class' => empty($item['active']) ? '' : 'text-bold']
                            ) ?>
                        </li>
                    <?php endforeach ?>
                </ul>
            </li>
        <?php endif ?>
    </ul>
<?php endif ?>
