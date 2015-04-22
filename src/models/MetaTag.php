<?php

namespace notgosu\yii2\modules\metaTag\models;

use notgosu\yii2\modules\metaTag\Module;
use Yii;

/**
 * This is the model class for table "{{%meta_tag}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $http_equiv
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
            [['name', 'description'], 'required'],
            [['default_value'], 'string'],
            [['position'], 'integer'],
            [['is_active'], 'boolean'],
            [['name', 'http_equiv', 'description'], 'string', 'max' => 255]
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
            'http_equiv' => Module::t('metaTag', 'HTTP equiv'),
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
