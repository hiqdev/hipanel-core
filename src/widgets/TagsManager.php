<?php

declare(strict_types=1);

namespace hipanel\widgets;

use hipanel\assets\Vue2CdnAsset;
use hipanel\helpers\Url;
use hipanel\models\TaggableInterface;
use Yii;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

class TagsManager extends Widget
{
    public TaggableInterface $model;

    public function run(): ?string
    {
        Vue2CdnAsset::register($this->view);

        $id = $this->getId();
        $objectId = $this->model->id;

        /** @var TagsInput $tagsInputWidget */
        $tagsInputWidget = Yii::createObject([
            'class' => TagsInput::class,
            'model' => $this->model,
            'templateOnly' => true,
        ]);
        $tagsInputWidget->init();
        $tagInput = $tagsInputWidget->run();
        $tag = Json::htmlEncode($tagInput);
        $mixin = $tagsInputWidget->getMixin();

        $this->view->registerJs(/** @lang JavaScript */ <<<"JS"
          (() => {

            (function ($) {
              "use strict";

              const Tags = function (options) {
                this.init('tags', options, Tags.defaults);
              };

              $.fn.editableutils.inherit(Tags, $.fn.editabletypes.text);

              $.extend(Tags.prototype, {
                input2value: function () {
                  const id = this.\$input.attr('id');
                  const value = $(`#\${id} input:hidden`).val();

                  return value;
                },
                value2input: function (value) {
                  this.\$input.filter('[type="hidden"]').val(Array.isArray(value) ? value.join(", ") : value);
                },
                render: function() {
                  this.setClass();
                  this.\$input.css({"width": "30rem", "display": "inline-block"});
                  const container = this.\$input;
                  const mixin = $mixin;
                  new Vue({
                    el: container.get(0),
                    mixins: [mixin],
                    data: {
                      objectId: "$objectId",
                      value: [],
                      options: container.find('input[type=hidden]').data('options'),
                    },
                    mounted() {
                      this.getTags(`?id=\${this.objectId}`, function(rsp) {
                        this.value = rsp.data.map(item => item.id);
                      }, this);
                    },
                  });
                }
              });

              Tags.defaults = $.extend({}, $.fn.editabletypes.list.defaults, {
                tpl: $tag,
              });

              $.fn.editabletypes.tags = Tags;

            }(window.jQuery));

            $("#$id").editable({ emptytext: "Tags", showbuttons: false, onblur: "submit" });

})();
JS
        );

        return Html::a(null, '#', [
            'id' => $id,
            'title' => 'Tags',
            'data' => [
                'id' => $this->model->id,
                'value' => implode(", ", $this->model->tags),
                'name' => 'tags',
                'type' => 'tags',
                'title' => Yii::t('hipanel', 'Tags'),
            ],
        ]);
    }
}
