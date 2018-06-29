<?php

namespace hipanel\widgets;

use hipanel\modules\client\widgets\combo\ClientCombo;
use hipanel\modules\finance\widgets\combo\PlanCombo;
use hipanel\modules\hosting\widgets\combo\AccountCombo;
use hipanel\modules\server\widgets\combo\ServerCombo;
use hipanel\modules\stock\widgets\combo\PartCombo;
use Yii;
use yii\bootstrap\InputWidget;

/**
 * Class BaseObjectSelector
 */
class ObjectSelector extends InputWidget
{
    public $object_type_attribute = 'class';

    public $object_type_real_attribute = 'class';

    public $objectMap = [
        'client' => ['alias' => '@client', 'combo' => ClientCombo::class],
        'device' => ['alias' => '@server', 'combo' => ServerCombo::class],
        'domain' => ['alias' => '@domain'],
        'zone' => ['alias' => '@zone'],
        'part' => ['alias' => '@part', 'combo' => PartCombo::class],
        'account' => ['alias' => '@account', 'combo' => AccountCombo::class],
        'plan' => ['alias' => '@plan', 'combo' => PlanCombo::class],
    ];

    /**
     * @inheritdoc
     */
    public function run()
    {
        return $this->render('ObjectSelector', [
            'model' => $this->model,
            'attribute' => $this->attribute,
            'objectOptions' => $this->getObjectOptions(),
            'availableObjects' => $this->getAvailableObjects(),
            'object_type_attribute' => $this->object_type_attribute,
            'object_type_real_attribute' => $this->object_type_real_attribute,
        ]);
    }

    private function getAvailableObjects(): array
    {
        $objects = [];
        foreach ($this->objectMap as $type => $options) {
            if (!Yii::getAlias($options['alias'], false)) {
                continue;
            }
            if ($options['combo']) {
                $options['comboOptions'] = get_class_vars($options['combo']);
            }
            $objects[$type] = $options;
        }

        return $objects;
    }

    private function getObjectOptions(): array
    {
        $dropDownOptions = [];
        foreach ($this->getAvailableObjects() as $type => $options) {
            $dropDownOptions[$type] = Yii::t('hipanel', $this->getLabel($type));
        }

        return $dropDownOptions;
    }

    private function getLabel($type): string
    {
        return $type === 'device' ? 'Server' : ucfirst($type);
    }
}

