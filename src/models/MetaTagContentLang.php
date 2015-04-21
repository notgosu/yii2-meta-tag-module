<?php

namespace notgosu\yii2\modules\metaTag\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%meta_tag_content_lang}}".
 *
 * @property integer $l_id
 * @property integer $model_id
 * @property string $lang_id
 * @property string $meta_tag_content
 */
class MetaTagContentLang extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%meta_tag_content_lang}}';
    }
}
