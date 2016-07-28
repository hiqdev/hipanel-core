<?php

namespace hipanel\models;

use hiqdev\hiart\ActiveQuery;

class ReminderQuery extends ActiveQuery
{
    public function toSite()
    {
        $this->andWhere([
            'to_site' => true
        ]);

        return $this;
    }
}
