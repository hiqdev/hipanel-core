<?php
declare(strict_types=1);

namespace hipanel\widgets;

use hipanel\modules\finance\assets\VueTreeSelectAsset;
use yii\base\InvalidConfigException;
use yii\widgets\InputWidget;

class VueTreeSelectInput extends InputWidget
{
    /**
     * @throws InvalidConfigException
     */
    public function init(): void
    {
        VueTreeSelectAsset::register($this->view);
        parent::init();
    }

    protected function removeKeysRecursively(array $items): array
    {
        foreach ($items as &$item) {
            if (isset($item['children'])) {
                $item['children'] = $this->removeKeysRecursively(array_values($item['children']));
            }
        }

        return $items;
    }
}
