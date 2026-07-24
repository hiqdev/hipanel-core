<?php

declare(strict_types=1);

namespace hipanel\widgets;

use hipanel\client\debt\models\DebtSearch;
use hipanel\helpers\Url;
use hipanel\models\TaggableInterface;
use yii\helpers\Html;
use yii\helpers\Json;

/**
 *
 * @property-read string $mixin
 * @property-read array $tagLinks
 * @property-read string $script
 */
class TagsInput extends VueTreeSelectInput
{
    public $name = 'tags';
    public $attribute = 'tags';
    public TaggableInterface $searchModel;
    public bool $templateOnly = false;

    public function init(): void
    {
        $this->searchModel ??= $this->model;
        parent::init();
    }

    public function run(): string
    {
        if ($this->model instanceof DebtSearch) {
            return '';
        }
        if (!$this->templateOnly) {
            $this->view->registerJs($this->getScript());
        }
        $activeInput = $this->buildActiveInput();
        $inputFieldset = $this->wrapToFieldset($activeInput);

        return $this->buildTemplate($inputFieldset);
    }

    public function wrapToFieldset(string $activeInput): string
    {
        $isAsync = $this->isAsync();

        return Html::tag('fieldset', $activeInput, ['disabled' => $isAsync === 'true']);
    }

    public function isAsync(): string
    {
        return str_contains($this->model->formName(), 'Search') ? 'false' : 'true';
    }

    public function getValue(): array
    {
        $value = Html::getAttributeValue($this->model, $this->attribute);
        if (empty($value)) {
            $value = [];
        } elseif (is_array($value)) {
            $value = array_values($value);
        } elseif (str_contains($value, ',')) {
            $value = explode(',', $value);
        } else {
            $value = [$value];
        }

        return $value;
    }

    public function buildActiveInput(): string
    {
        return Html::activeHiddenInput($this->model, $this->attribute, [
            'v-model' => 'value',
            'value' => '',
            'data' => [
                'value' => $this->getValue(),
                'options' => Json::encode($this->buildOptions()),
            ],
        ]);
    }

    public function buildTemplate(string $inputField): string
    {
        return preg_replace(["/\s\s+/"],
            ' ',
            <<<"HTML"
            <span id="$this->id" style="margin-bottom: 1em;">
                <treeselect
                  placeholder="{$this->model->getAttributeLabel($this->attribute)}"
                  v-model="value"
                  :clearable="false"
                  :always-open="false"
                  :options="options"
                  :load-options="loadOptions"
                  :multiple="true"
                  :async="{$this->isAsync()}"
                  @select="saveTags"
                  @deselect="saveTags"
                >
                    <div slot="value-label" slot-scope="{ node }">{{ node.raw.id }}</div>
                </treeselect>
                $inputField
            </span>
HTML
        );
    }

    public function getTagLinks(): array
    {
        $alias = str_replace("search", "", strtolower($this->searchModel->formName()));
        $getTagsLink = Url::to("@$alias/get-tags");
        $setTagsLink = Url::to("@$alias/set-tags");

        return [$getTagsLink, $setTagsLink];
    }

    public function getScript(): string
    {
        $mixin = $this->getMixin();

        return <<<"JS"
          ;(() => {
            const container = $("#$this->id");
            const mixin = $mixin;
            new Vue({
              el: container.get(0),
              mixins: [mixin],
              data: function () {
                return {
                  value: container.find("input[type=hidden]").data("value"),
                  options: container.find("input[type=hidden]").data("options"),
                };
              },
            });
          })();
JS;

    }

    public function getMixin(): string
    {
        [$getTagsLink, $setTagsLink] = $this->getTagLinks();

        return /** @lang JavaScript */ <<<JS
            {
              components: {
                "treeselect": VueTreeselect.Treeselect,
              },
              data: function () {
                return {
                  objectId: null,
                  value: [],
                  options: [],
                };
              },
              methods: {
                saveTags: function () {
                  if (this.objectId) {
                    this.\$nextTick(() => {
                      $.post("$setTagsLink", {id: this.objectId, tags: this.value}).done((rsp) => {
                        if (rsp.hasError) {
                          hipanel.notify.error(rsp.data.errorMessage);
                        }
                      }).fail(function(err) {
                        console.error(err.responseText);
                        hipanel.notify.error("Failed to save tags");
                      });
                    });
                  }
                },
                loadOptions: function({ action, searchQuery, callback }) {
                  if (action === "ASYNC_SEARCH") {
                    const typedValue = [{
                      id: `\${searchQuery}`,
                      label: `\${searchQuery}`,
                    }];
                    let query = "";
                    if (searchQuery) {
                      query = `?tagLike=\${searchQuery}`;
                    }
                    this.getTags(query, function(rsp) {
                      const tags = rsp.data.filter(item => item.id !== searchQuery);
                      callback(null, typedValue.concat(tags));
                    }, this);
                  }
                },
                getTags: function (searchQeury, callback, callbackObject) {
                  $.get(`$getTagsLink\${searchQeury}`).done((rsp) => {
                    if (rsp.hasError) {
                      hipanel.notify.error(rsp.data.errorMessage);
                    } else {
                      callback.apply(callbackObject, [rsp]);
                    }
                  }).fail(function(err) {
                    console.error(err.responseText);
                    hipanel.notify.error("Failed to get tags");
                  });
                }
              },
           }

JS;
    }

    public function buildOptions(): array
    {
        $availableTags = $this->searchModel->fetchTags();
        $options = [];
        foreach ($availableTags as $tag) {
            $options[] = ['id' => $tag['tag'], 'label' => $tag['tag']];
        }

        return $options;
    }
}
