<?php
namespace app\modules\client\models;

use Yii;

class Article extends \yii\db\ActiveRecord
{
    public $id;
    public $article_name;
    public $author_id;
    public $post_date;
    public $type;
    public $type_id;
    public $type_name;
    public $is_published;


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
}