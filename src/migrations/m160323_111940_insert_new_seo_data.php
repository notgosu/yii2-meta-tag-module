<?php

use yii\db\Schema;

/**
 * Class m160323_111940_insert_new_seo_data migration
 */
class m160323_111940_insert_new_seo_data extends \yii\db\Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%meta_tag}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->batchInsert(
            $this->tableName,
            [
                'name',
                'description',
                'is_active',
                'position',
                'content',
            ],
            [
                [
                    \notgosu\yii2\modules\metaTag\models\MetaTag::META_ROBOTS,
                    'robots no index, FOLLOW',
                    1,
                    0,
                    0,
                ],
                [
                    \notgosu\yii2\modules\metaTag\models\MetaTag::META_SEO_TEXT,
                    'Seo Text',
                    1,
                    0,
                    null,
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
    }
}
