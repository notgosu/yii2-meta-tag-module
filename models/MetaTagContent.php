<?php

namespace notgosu\yii2\modules\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%meta_tag_content}}".
 *
 * @property integer $id
 * @property string $model_name
 * @property string $model_id
 * @property integer $meta_tag_id
 * @property string $meta_tag_content
 * @property string $description
 * @property string $created
 * @property string $modified
 *
 * @property MetaTag $metaTag
 * @property MetaTagContentLang[] $metaTagContentLangs
 */
class MetaTagContent extends ActiveRecord
{
    public $tagName;

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
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                [
                    'class' => TimestampBehavior::className(),
                    'createdAtAttribute' => 'created',
                    'updatedAtAttribute' => 'modified',
                    'value' => function () {
                        return date("Y-m-d H:i:s");
                    }
                ],
            ]
        );
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetaTag()
    {
        return $this->hasOne(MetaTag::className(), ['id' => 'meta_tag_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetaTagContentLangs()
    {
        return $this->hasMany(MetaTagContentLang::className(), ['model_id' => 'id']);
    }
}
