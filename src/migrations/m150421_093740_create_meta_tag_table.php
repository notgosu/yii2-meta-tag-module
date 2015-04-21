<?php

use yii\db\Schema;

/**
 * Class m150421_093740_create_meta_tag_table migration
 */
class m150421_093740_create_meta_tag_table extends \yii\db\Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%meta_tag_content}}';

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            $this->tableName,
            [
                'model_name' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Model name"',
                'model_id' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL COMMENT "Model ID"',
                'language' => Schema::TYPE_STRING . '(16) NOT NULL COMMENT "Language"',

                'meta_tag_id' => Schema::TYPE_INTEGER . ' NOT NULL COMMENT "Meta tag"',
                'meta_tag_content' => Schema::TYPE_TEXT . ' NOT NULL COMMENT "Meta tag content"',
            ],
            $tableOptions
        );

        $this->addPrimaryKey('', $this->tableName, ['model_name', 'model_id', 'language', 'meta_tag_id']);
        $this->createIndex('index-model_name-model_id-language', $this->tableName, ['model_name', 'model_id', 'language']);

        $this->addForeignKey(
            'fk-meta_tag_content-meta_tag_id-meta_tag-id',
            $this->tableName,
            'meta_tag_id',
            '{{%meta_tag}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
