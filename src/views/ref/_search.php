<?php

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 * @var \hipanel\models\IndexPageUiOptions $uiModel
 * @var \yii\web\View $this
 */

$this->registerJs(<<<JS

(() => {
    function changeableHandler() {
        const context = this;
        const contextValue = $(context).children("option:selected").val();
        const contextNumber = $(context).data().num;
        
        $.ajax({
            method: "GET",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            url: "/ref/get-child?parent=" + contextValue
        }).done(function (result) {
            if (result.length === 0) {
                return;
            }

            $(`select[class="changeable"]`).each(function () {
                if ($(this).data().num > contextNumber) {
                    $(this).parent().parent().remove();  
                }
            })

            let options = Object.keys(result).map(function (value) {
                const newValue = contextValue + ',' + value;
                return `<option value="\${newValue}">\${value}</option>`;
            });
            options.push(`<option value="" selected>---</option>`);
            
            $('<div class="col-md-4 col-sm-6 col-xs-12 ">' +
                '<div class="form-group field-refsearch-gtype" data-toggle="tooltip" data-title="Gtype" data-original-title="" title="" xpath="1">' +
                    `<select id="refsearch-gtype" class="changeable" data-num="\${contextNumber + 1}">` +
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
        
        const keyToSave = $(`select[class="changeable"][data-num="\${maxFilter}"])`).children("option:selected").val() !== ""
                            ? maxFilter
                            : maxFilter - 1;
        
        $(`select[class="changeable"]:not([data-num="\${keyToSave}"])`).remove();
        debugger;
    });
    
    function renderElems() {
    
        $('.changeable').change(changeableHandler);
    }
    
    renderElems();
})();

JS
);

?>

