<?php
declare(strict_types=1);

namespace hipanel\models;

use hipanel\base\Model;
use hipanel\base\ModelTrait;

class Tag extends Model
{
    use ModelTrait;

    public function rules()
    {
        return [
            [['frequency'], 'integer'],
            [['tag'], 'string'],
        ];
    }
}
