<?php declare(strict_types=1);

namespace hipanel\widgets;

use yii\bootstrap\ButtonDropdown;
use yii\bootstrap\InputWidget;
use yii\helpers\Html;

/**
 *
 * @property-read SearchBy[] $variants
 */
class SearchManagedField extends InputWidget
{
    public string $template = '{input}';
    public ?SearchBy $default = null;
    /** @var SearchBy[] */
    public array $searchBy = [];

    public function init(): void
    {
        parent::init();

        $this->options['class'] = ($this->options['class'] ?? '') . ' form-control';
        $items = [];
        if (!$this->default) {
            $items[] = [
                'label' => $this->attribute,
                'url' => '#',
                'linkOptions' => ['data' => ['condition' => $this->attribute]],
            ];
        }
        $variants = $this->getVariants();
        [$condition] = $this->currentCnd();
        if (!empty($variants)) {
            foreach ($this->getVariants() as $variant) {
                $v = implode('_', array_filter([$this->attribute, $variant->value]));
                $items[] = [
                    'label' => $v,
                    'url' => '#',
                    'linkOptions' => ['data' => ['condition' => $v]],
                ];
            }
            $buttonDropdown = ButtonDropdown::widget([
                'label' => Html::tag('span', implode('_', array_filter([$this->attribute, $condition->value])), ['class' => 'condition']),
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

    /**
     * @return SearchBy[]
     */
    private function getVariants(): array
    {
        return array_uintersect(SearchBy::cases(), $this->searchBy, static fn($a, $b) => strcmp(spl_object_hash($a), spl_object_hash($b)));
    }

    private function currentCnd(): array
    {
        $queryParams = $this->view->context->request->get($this->model->formName());
        if ($queryParams) {
            foreach ($queryParams as $param => $value) {
                foreach ($this->getVariants() as $searchVariant) {
                    $attributeName = empty($searchVariant) ? $this->attribute : $this->attribute . '_' . $searchVariant->value;
                    if ($param === $attributeName) {
                        return [$searchVariant, $value];
                    }
                }
            }
        }

        return [$this->attribute, $queryParams[$this->attribute] ?? ''];
    }

    private function registerClientScript(): void
    {
        $inputId = $this->options['id'];
        [$condition, $value] = $this->currentCnd();
        $condition = $condition instanceof SearchBy ? $condition->value : $condition;
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
