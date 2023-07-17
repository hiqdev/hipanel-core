<?php
declare(strict_types=1);

namespace hipanel\widgets;

use yii\caching\CacheInterface;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\web\User;

class TagsInput extends VueTreeSelectInput
{
    public $name = 'tags';
    public $attribute = 'tags';

    public function __construct(
        private readonly CacheInterface $cache,
        private readonly User $user,
        $config = []
    )
    {
        parent::__construct($config);
    }

    public function run(): string
    {
        $this->registerJs();
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
        $activeInput = Html::activeHiddenInput($this->model, $this->attribute, [
            'v-model' => 'value',
            'value' => null,
            'data' => [
                'value' => $value,
                'options' => Json::encode($this->buildOptions()),
            ],
        ]);
        $isAsync = str_contains($this->model->formName(), 'Search') ? 'false' : 'true';

        return <<<"HTML"
            <span id="$this->id" style="margin-bottom: 1em;">
                <treeselect
                  placeholder="{$this->model->getAttributeLabel($this->attribute)}"
                  v-model="value"
                  :clearable="false"
                  :always-open="false"
                  :options="options"
                  :load-options="loadOptions"
                  :multiple="true"
                  :async="$isAsync"
                  @input="saveTags"
                >
                    <div slot="value-label" slot-scope="{ node }">{{ node.raw.id }}</div>
                </treeselect>
                $activeInput
            </span>
HTML;
    }

    private function registerJs(): void
    {
        $this->view->registerJs(<<<"JS"
            ;(() => {
                const container = $("#$this->id");
                new Vue({
                    el: container.get(0),
                    components: {
                      'treeselect': VueTreeselect.Treeselect,
                    },
                    data: {
                        value: container.find("input[type=hidden]").data("value"),
                        options: container.find('input[type=hidden]').data('options'),
                    },
                    methods: {
                      saveTags: function () {
                        const entityId = '{$this->model->id}';
                        if (entityId.length) {
                          $.post("set-tags", {id: entityId, tags: this.value}).done((rsp) => {
                            if (rsp.hasError) {
                              hipanel.notify.error(rsp.data.errorMessage);
                            }
                          }).fail(function(err) {
                            console.error(err.responseText);
                            hipanel.notify.error("Failed to save tags");
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
                          $.get(`get-tags\${query}`).done((rsp) => {
                            if (rsp.hasError) {
                              hipanel.notify.error(rsp.data.errorMessage);
                            } else {
                              const tags = rsp.data.filter(item => item.id !== searchQuery);
                              callback(null, typedValue.concat(tags));
                            }
                          }).fail(function(err) {
                            console.error(err.responseText);
                            hipanel.notify.error("Failed to get tags");
                          });
                        }
                      },
                    },
                });
            })();
JS
        );

    }

    private function buildOptions(): array
    {
        $availableTags = $this->cache->getOrSet(
            [$this->model->formName(), $this->user->id],
            fn() => $this->model->fetchTags(),
            10 // in seconds
        );
        $options = [];
        foreach ($availableTags as $tag) {
            $options[] = ['id' => $tag['tag'], 'label' => $tag['tag']];
        }

        return $options;
    }
}
