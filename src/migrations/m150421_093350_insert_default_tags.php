<?php

use yii\db\Schema;

/**
 * Class m150421_093350_insert_default_tags migration
 */
class m150421_093350_insert_default_tags extends \yii\db\Migration
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
                    \notgosu\yii2\modules\metaTag\models\MetaTag::META_TITLE_NAME,
                    'Page title',
                    1,
                    3,
                ],
                [
                    'keywords',
                    'Page keywords',
                    1,
                    2,
                ],
                [
                    'description',
                    'Page description',
                    1,
                    1,
                ],
            ]
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->truncateTable($this->tableName);
    }
}
