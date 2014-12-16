<?php
namespace app\modules\client\models;

use Yii, frontend\models\Ref;

class Client extends \frontend\components\hiresource\ActiveRecord
{
    /**
     * @return array the list of attributes for this record
     */
    public function attributes()
    {
        return [
            // search
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
            [[
                'name',
                'type',
            ],'required'],
            [[
                'type',
            ],'safe'],
        ];
    }

    public function rest()
    {
        return \yii\helpers\ArrayHelper::merge(parent::rest(),['resource'=>'article']);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'article_name' => Yii::t('app', 'Article Name'),
            'post_date' => Yii::t('app', 'Post Date'),
            'data' => Yii::t('app', 'Data'),
        ];
    }
}
