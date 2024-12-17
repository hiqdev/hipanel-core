<?php declare(strict_types=1);

namespace hipanel\widgets;

use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\InputWidget;
use yii\helpers\Html;

class SearchManagedField extends InputWidget
{
    public string $template = '{input}';
    public array $searchBy = [];

    public function init(): void
    {
        parent::init();

        $this->options['class'] = ($this->options['class'] ?? '') . ' form-control';
        $items = [];
        $variants = $this->searchVariants();
        [$condition] = $this->currentCnd();
        if (!empty($variants)) {
            foreach ($this->searchVariants() as $variant) {
                $v = implode('_', array_filter([$this->attribute, $variant]));
                $items[] = [
                    'label' => $v,
                    'url' => '#',
                    'linkOptions' => ['data' => ['condition' => $v]],
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
            $this->template = strtr('<div class="input-group">{input}<span class="input-group-btn">{btn}</span></div>', [
                '{btn}' => $buttonDropdown,
            ]);
            $this->registerClientScript();
        }
    }

    public function run(): string
    {
        return $this->renderInputHtml('text');
    }

    private function searchVariants(): array
    {
        $result = array_intersect(['in', 'like', 'ilike', 'likei', 'leftLikei'], $this->searchBy);
        array_unshift($result, '');

        return $result;
    }

    private function currentCnd(): array
    {
        $options = $this->view->context->request->get($this->model->formName());
        if ($options) {
            foreach ($options as $option => $value) {
                foreach ($this->searchVariants() as $searchVariant) {
                    $attributeName = empty($searchVariant) ? $this->attribute : $this->attribute . '_' . $searchVariant;
                    if ($option === $attributeName) {
                        return [$searchVariant, $value];
                    }
                }
            }
        }

        return [$this->attribute, ''];
    }

    private function registerClientScript(): void
    {
        $inputId = $this->options['id'];
        [$condition, $value] = $this->currentCnd();
        $formName = $this->model->formName();
        $this->view->registerJs(<<<"JS"
          ;(() => {
            const updateInput = (condition) => {
              \$("#$inputId").attr("name", `{$formName}[\${condition}]`).val("$value");
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
