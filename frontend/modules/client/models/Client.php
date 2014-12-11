<?php
namespace frontend\modules\client\models;

use Yii, frontend\models\Ref;


class Client extends \frontend\components\hiresource\ActiveRecord
{
    /**
     * @return array|\string[]
     */
    public function attributes()
    {
        return [
            // serach
            'id',
            'client',
            'seller',
            'type',
            'state',
            'hide_blocked',
            'show_deleted',
            'show',
            'tariff_id',
            'direct_only',
            'with_domains_count',
            'with_servers_count',
            'with_contact',
            'manager_only',
            'view',

            // getlist
            'client_like',
            'direct_only',
            'clients',

            // create/update
            'password',
            'email',
            'referer',
            'language',
            'confirm_url',
            // with_contact
            'login',
            'name',
            'first_name',
            'last_name',
        ];
    }

    public function rules()
    {
        return [

        ];
    }
}