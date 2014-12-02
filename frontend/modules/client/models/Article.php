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
        return ['id', 'article_name', 'post_date', 'data'];
    }


    public function rules()
    {
        return [
            [['article_name'],'required'],
            [[
                'post_date',
                'data',
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