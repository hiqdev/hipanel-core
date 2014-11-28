<?php
namespace app\modules\client\models;

use Yii;

class Article extends \frontend\components\hiresource\ActiveRecord
{

    /**
     * @return array the list of attributes for this record
     */
    public function attributes()
    {
        // path mapping for '_id' is setup to field 'id'
        return ['id', 'article_name', 'author_id', 'post_date'];
    }


    public function rules()
    {
        return [
            [['article_name'],'required'],
            [[
                'author_id',
                'post_date',
                'type',
                'type_id',
                'type_name',
                'is_published',
            ],'safe'],
        ];
    }

    public function rest()
    {
        return \yii\helpers\ArrayHelper::merge(parent::rest(),['resource'=>'article']);
    }
}