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
                'model_name' => $this->string(50)->notNull()->comment('Model name'),
                'model_id' => $this->integer()->notNull()->comment('Model ID'),
                'language' => $this->string(16)->notNull()->comment('Language'),
                'meta_tag_id' => $this->integer()->notNull()->comment('Meta tag'),
                'content' => $this->text()->defaultValue(null)->comment('Meta tag content'),
            ],
            $tableOptions
        );

        $this->addPrimaryKey('meta_tag_content_pk', $this->tableName, ['model_name', 'model_id', 'language', 'meta_tag_id']);
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
