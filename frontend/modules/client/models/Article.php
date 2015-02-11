<?php
namespace frontend\modules\client\models;

use Yii, frontend\models\Ref;

class Article extends \frontend\components\hiresource\ActiveRecord
{

    public function getArticleExtraFields() {
        $extraField = [
            'html_title',
            'html_keywords',
            'html_description',
            'title',
            'short_text',
            'text',
        ];
        $lngs = self::getApiLangs();
        foreach ($extraField as $field) {
            foreach ( $lngs as $l) {
                $ar[] = $field.'_'.$l->gl_key;
            }
        }
        return $ar;
    }

    /**
     * @return array the list of attributes for this record
     */
    public function attributes()
    {

        return [
            'id',
            'article_name',
            'post_date',
            'data',
            // create
            'name',
            'type',
            'texts',
            'client',
            'client_id',
            'is_common',
            'realm',
            'is_published',

        ];
    }

    public static function getApiLangs($select=null) {
        if ($select!==null)
            return Ref::find()->where(['gtype'=>'type,lang','select'=>$select])->getList();
        else
            return Ref::find()->where(['gtype'=>'type,lang'])->getList();

    }

    public function rules()
    {
        return [
            [[
                'name',
                'type',
            ],'required'],
            [[
                'is_published',
                'type',
                'post_date',
                'data',
                'texts',
            ],'safe'],
        ];
    }

//    public function fields()
//    {
//        return [
//            'lang_id',
//            'html_title',
//            'html_keywords',
//            'title',
//            'short_text',
//            'text',
//            'name',
//            'label',
//        ];
//    }
//
//    public function extraFields()
//    {
//        return ['data'];
//    }

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