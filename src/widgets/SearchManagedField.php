<?php

declare(strict_types=1);

namespace hipanel\widgets;

use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\InputWidget;
use yii\helpers\Html;

class SearchManagedField extends InputWidget
{
    public string $default = 'eq';
    public string $template = '{input}';
    public array $searchBy = [];

    public function init(): void
    {
        parent::init();

        $items = [];
        $variants = $this->searchVariants();
        [$condition] = $this->currentCnd();
        if (!empty($variants)) {
            foreach ($this->searchVariants() as $variant) {
                $items[] = [
                    'label' => $variant,
                    'url' => '#',
                    'linkOptions' => ['data' => ['condition' => $variant]],
                ];
            }
            $buttonDropdown = ButtonDropdown::widget([
                'label' => Html::tag('span', $condition, ['class' => 'condition']),
                'options' => ['class' => 'btn-default btn-sm'],
                'encodeLabel' => false,
                'dropdown' => [
                    'items' => $items,
                ],
            ]);
            $this->template = strtr(
                '<div class="input-group">{input}<span class="input-group-btn">{btn}</span></div>',
                ['{btn}' => $buttonDropdown]
            );
            $this->registerClientScript();
        }
    }

    private function searchVariants(): array
    {
        return array_intersect(
            ['and', 'between', 'eq', 'ne', 'in', 'ni', 'like', 'ilike', 'likei', 'leftLikei', 'gt', 'ge', 'lt', 'le'],
            $this->searchBy
        );
    }

    private function currentCnd(): array
    {
        $options = $this->view->context->request->get($this->model->formName());
        foreach ($options as $option => $value) {
            foreach ($this->searchVariants() as $searchVariant) {
                $attributeName = $this->attribute . '_' . $searchVariant;
                if ($option === $attributeName) {
                    return [$searchVariant, $value];
                }
            }
        }

        return [$this->default, ''];
    }

    private function registerClientScript(): void
    {
        $inputId = $this->options['id'];
        [$condition, $value] = $this->currentCnd();
        $formName = $this->model->formName();
        $this->view->registerJs(<<<"JS"
          ;(() => {
            const updateInput = (condition) => {
              \$("#$inputId").attr("name", `{$formName}[{$this->attribute}_\${condition}]`).val("$value");
            };
            const updateButtonDropdown = (condition) => {
              \$("#$inputId").siblings('span').find('button span.condition').text(condition);
            };
            updateInput('$condition');
            \$("#$inputId").siblings('span').find('ul a').on('click', function(e) {
              e.preventDefault();
              updateInput(e.target.dataset.condition);
              updateButtonDropdown(e.target.dataset.condition);
            });
          })();
JS
        );
    }
}
