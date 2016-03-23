<?php

namespace notgosu\yii2\modules\metaTag\models;

use notgosu\yii2\modules\metaTag\Module;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%meta_tag_content}}".
 *
 * @property string $model_name
 * @property integer $model_id
 * @property string $language
 * @property integer $meta_tag_id
 * @property string $content
 *
 * @property MetaTag $metaTag
 */
class MetaTagContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%meta_tag_content}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_name', 'model_id', 'language', 'meta_tag_id'], 'required'],
            [['model_id', 'meta_tag_id'], 'integer'],
            [['content'], 'string'],
            [['model_name'], 'string', 'max' => 50],
            [['language'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'model_name' => Module::t('metaTag', 'Model name'),
            'model_id' => Module::t('metaTag', 'Model ID'),
            'language' => Module::t('metaTag', 'Language'),
            'meta_tag_id' => Module::t('metaTag', 'Meta tag'),
            'content' => Module::t('metaTag', 'Meta tag content'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetaTag()
    {
        return $this->hasOne(MetaTag::className(), ['id' => 'meta_tag_id']);
    }

    /**
     * @return string
     */
    public function getMetaTagContent()
    {
        $content = $this->content;
        $page = Yii::$app->request->get('page');

        if ($this->metaTag->name == MetaTag::META_TITLE_NAME || $this->metaTag->name == MetaTag::META_DESCRIPTION_NAME
            && isset($page) && $page > 1
        ) {
            if (!empty($content)) {
                $content = Module::t('metaTag', 'Page') . ' ' . $page . '. ' . $content;
            }
        }
        return $content;
    }
}
