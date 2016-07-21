<?php

use hipanel\modules\client\widgets\combo\ClientCombo;

/**
 * @var \hipanel\widgets\AdvancedSearch $search
 */
?>
<div class="col-md-4 col-sm-6 col-xs-12">
    <?= $search->field('client_id')->widget(ClientCombo::class) ?>
</div>
