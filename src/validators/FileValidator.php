<?php

declare(strict_types=1);

namespace hipanel\validators;

use hipanel\models\Ref;
use Yii;

class FileValidator extends \yii\validators\FileValidator
{
    public function init(): void
    {
        $this->tooMany = Yii::t('hipanel', 'Number of files selected for upload exceeds maximum allowed limit of {limit}');
        $refs = implode(', ',
            array_map(
                fn(Ref $model) => trim($model->name),
                Ref::findCached('type,file')
            ));
        $this->extensions = $refs;
        parent::init();
    }
}
