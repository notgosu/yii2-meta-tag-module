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
            ],
            [
                [
                    \notgosu\yii2\modules\metaTag\models\MetaTag::META_ROBOTS,
                    'robots no index, FOLLOW',
                    1,
                    5,
                ],
                [
                    \notgosu\yii2\modules\metaTag\models\MetaTag::META_SEO_TEXT,
                    'Seo Text',
                    1,
                    4,
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
