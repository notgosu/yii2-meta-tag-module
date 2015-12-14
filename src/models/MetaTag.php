<?php

namespace notgosu\yii2\modules\metaTag\models;

use notgosu\yii2\modules\metaTag\Module;
use Yii;

/**
 * This is the model class for table "{{%meta_tag}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $is_http_equiv
 * @property string $default_value
 * @property string $description
 * @property integer $is_active
 * @property integer $position
 *
 * @property MetaTagContent[] $metaTagContents
 */
class MetaTag extends \yii\db\ActiveRecord
{
    const META_TITLE_NAME = 'title';
    const META_DESCRIPTION_NAME = 'description';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%meta_tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'unique'],
            [['default_value'], 'string'],
            [['position'], 'integer'],
            [['is_active', 'is_http_equiv'], 'boolean'],
            [['name', 'description'], 'string', 'max' => 255],
            [['position'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Module::t('metaTag', 'ID'),
            'name' => Module::t('metaTag', 'Meta tag name'),
            'is_http_equiv' => Module::t('metaTag', 'Is HTTP equiv'),
            'default_value' => Module::t('metaTag', 'Default value'),
            'description' => Module::t('metaTag', 'Description'),
            'is_active' => Module::t('metaTag', 'Registered on page'),
            'position' => Module::t('metaTag', 'Position'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetaTagContents()
    {
        return $this->hasMany(MetaTagContent::className(), ['meta_tag_id' => 'id']);
    }
}
