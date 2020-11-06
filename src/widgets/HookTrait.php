<?php

namespace hipanel\widgets;

use hipanel\actions\Action;
use yii\web\View;

trait HookTrait
{
    public function registerJsHook(string $reqHeaderParamName): void
    {
        $id = $this->getId();
        $headerName = Action::EXPECTED_AJAX_RESPONSE_HEADER_NAME;
        $this->view->registerJs("$.ajax({
           type: 'GET',
           url: document.URL,
           beforeSend: xhr => {
             xhr.setRequestHeader('{$headerName}', '{$reqHeaderParamName}');
           },
           success: html => {
             $('#{$id}').html(html);
           }
        });", View::POS_LOAD);
    }
}
