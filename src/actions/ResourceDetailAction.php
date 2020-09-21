<?php

namespace hipanel\actions;

class ResourceDetailAction extends IndexAction
{
    public function beforePerform()
    {
        parent::beforePerform();
        $query = $this->getDataProvider()->query;
        $query->andWhere([
            'time_from' => '2020-08-01',
            'time_till' => '2020-08-31',
        ]);
        $query->andWhere([
            'object_id' => $this->request->get('id'),
            'groupby' => 'server_traf_day',
        ]);
    }
}