<?php

namespace notgosu\yii2\modules\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%meta_tag}}".
 *
 * @property integer $id
 * @property string $meta_tag_name
 * @property string $meta_tag_http_equiv
 * @property string $meta_tag_default_value
 * @property integer $is_active
 * @property integer $position
 * @property string $created
 * @property string $modified
 *
 * @property MetaTagContent[] $metaTagContents
 */
class MetaTag extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%meta_tag}}';
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMetaTagContents()
    {
        return $this->hasMany(MetaTagContent::className(), ['meta_tag_id' => 'id']);
    }
}
