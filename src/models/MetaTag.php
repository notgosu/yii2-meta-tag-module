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
            [['name', 'http_equiv', 'description'], 'required'],
            [['default_value'], 'string'],
            [['is_active', 'position'], 'integer'],
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
            'name' => Module::t('metaTag', 'Specifies a name for the metadata'),
            'http_equiv' => Module::t('metaTag', 'Provides an HTTP header for the information/value of the content attribute'),
            'default_value' => Module::t('metaTag', 'Default value for the meta tag'),
            'description' => Module::t('metaTag', 'Short description for tag'),
            'is_active' => Module::t('metaTag', 'Register or not this tag on the front'),
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
