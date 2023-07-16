<?php
declare(strict_types=1);

namespace hipanel\widgets;

use yii\helpers\Html;
use yii\helpers\Json;

class TagsInput extends VueTreeSelectInput
{
    public $name = 'tags';
    public $attribute = 'tags';

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

        return <<<"HTML"
            <div id="$this->id" style="margin-bottom: 1em;">
                <treeselect
                  placeholder="{$this->model->getAttributeLabel($this->attribute)}"
                  v-model="value"
                  :clearable="false"
                  :always-open="false"
                  :options="options"
                  :load-options="loadOptions"
                  :multiple="true"
                  :async="true"
                  @input="saveTags"
                >
                    <div slot="value-label" slot-scope="{ node }">{{ node.raw.id }}</div>
                </treeselect>
                $activeInput
            </div>
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
                        // options: null,
                    },
                    methods: {
                      saveTags: function () {
                        $.post("set-tags", {id: '{$this->model->id}', tags: this.value}).done((response) => {
                        }).fail(function(err) {
                          console.error(err.responseText);
                          hipanel.notify.error("Failed to save tags");
                        });
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
                            callback(null, typedValue.concat(rsp.data));
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
        $options = [];
        foreach ($this->model->tags as $tag) {
            $options[] = ['id' => $tag, 'label' => $tag];
        }

        return $options;
    }
}
