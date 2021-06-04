<?php

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 * @var \hipanel\models\IndexPageUiOptions $uiModel
 * @var \yii\web\View $this
 */

use hipanel\models\Ref;
use hipanel\models\RefSearch;

$this->registerJs(<<<JS

(() => {
    function removeElementsAfter(number) {
        $(`select[class="changeable"]`).each(function () {
            if ($(this).data().num > number) {
                $(this).parent().parent().remove();
            }
        });
    }

    function changeableHandler() {
        const context = this;
        const contextValue = $(context).children("option:selected").val();
        const contextNumber = $(context).data().num;
        
        if (contextValue === "") {
            removeElementsAfter(contextNumber);
            return;
        }

        $.ajax({
            method: "GET",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            url: "/ref/get-child?parent=" + contextValue
        }).done(function (result) {
            if (result.length === 0) {
                return;
            }

            removeElementsAfter(contextNumber);

            let options = Object.keys(result).map(function (value) {
                const newValue = contextValue + ',' + value;
                return `<option value="\${newValue}">\${value}</option>`;
            });
            options.push(`<option value="" selected>----------</option>`);
            
            $('<div class="col-md-4 col-sm-6 col-xs-12 ">' +
                '<div class="form-group field-refsearch-gtype" data-toggle="tooltip" data-title="Gtype" data-original-title="" title="" xpath="1">' +
                    `<select id="refsearch-gtype" class="changeable" name="RefSearch[gtype]" data-num="\${contextNumber + 1}">` +
                        options.join() +
                    '</select>' +
                '</div>' +
              '</div>')
              .insertAfter($(context).parent().parent());
            
            $('.changeable').change(changeableHandler);
        });
    }
    
    $("#form-advancedsearch-ref-search").submit(function(event) {
        const maxFilter = parseInt(Math.max.apply(Math, $(`select[class="changeable"]`).map(function() { return $(this).data().num; })));
        
        const keyToSave = $(`select[class="changeable"][data-num="\${maxFilter}"]`).children("option:selected").val() !== ""
                            ? maxFilter
                            : maxFilter - 1;
        
        $(`select[class="changeable"]:not([data-num="\${keyToSave}"])`).parent().parent().remove();
    });
    
    $('.changeable').change(changeableHandler);
})();

JS
);

$request = Yii::$app->request->get();
$gtype = $request['RefSearch']['gtype'] ?? RefSearch::DEFAULT_SEARCH_ATTRIBUTE;
$refBuild = '';
$gtypeParts = ['', ...explode(',', $gtype)];

?>

<?php foreach ($gtypeParts as $key => $refPart): ?>
    <?php
        $refBuild = implode(',', array_filter([$refBuild, $refPart]));
        $childs = array_keys(Ref::getList($refBuild));
        $arrayRefs = array_combine($childs, $childs);
        $arrayRefs = array_map(
            fn ($el) => implode(',', array_filter([$refBuild, $el])),
            $arrayRefs
        );
    ?>
    <div class="col-md-4 col-sm-6 col-xs-12 ">
        <?= $search->field('gtype')->dropDownList(array_flip($arrayRefs), [
            'class' => 'changeable',
            'data-num' => $key + 1,
            'prompt'    => Yii::t('hipanel', '----------'),
            'options' => [
                $arrayRefs[$gtypeParts[$key + 1]] ?? '' => ['selected' => true],
            ],
        ]) ?>
    </div>
<?php endforeach; ?>
