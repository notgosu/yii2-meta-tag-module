<?php

use yii\db\Schema;
use yii\db\Migration;

/**
* Class m150325_103627_add_meta_tag_tables*/
class m150325_103627_add_meta_tag_tables extends Migration
{
    /**
     * migration table name
     */
    public $tableName = '{{%meta_tag}}';

    /**
     * @var string
     */
    public $relatedTableName = '{{%meta_tag_content}}';

    /**
    * commands will be executed in transaction
    */
    public function safeUp()
    {
        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'meta_tag_name' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Specifies a name for the metadata"',
                'meta_tag_http_equiv' => Schema::TYPE_STRING. ' NOT NULL COMMENT "Provides an HTTP header for the information/value of the content attribute"',
                'meta_tag_default_value' => Schema::TYPE_TEXT. ' DEFAULT NULL COMMENT "Default value for the meta tag"',
                'is_active' => Schema::TYPE_SMALLINT. '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Register or not this tag on the front"',
                'position' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL DEFAULT 0',
                'created' => Schema::TYPE_DATETIME. ' NOT NULL',
                'modified' => Schema::TYPE_DATETIME. ' NOT NULL',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->createTable(
            $this->relatedTableName,
            [
                'id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
                'model_name' => Schema::TYPE_STRING. ' NOT NULL',
                'model_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'meta_tag_id' => Schema::TYPE_INTEGER. ' UNSIGNED NOT NULL',
                'meta_tag_content' => Schema::TYPE_TEXT. ' DEFAULT NULL',
                'created' => Schema::TYPE_DATETIME. ' NOT NULL',
                'modified' => Schema::TYPE_DATETIME. ' NOT NULL',
            ],
            'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB'
        );

        $this->addForeignKey(
            'fk_meta_tag_id_to_meta_tag_table_id',
            $this->relatedTableName,
            'meta_tag_id',
            $this->tableName,
            'id',
            'CASCADE',
            'CASCADE'
        );

        $date = (new DateTime())->format('Y-m-d H:i:s');

        $this->batchInsert($this->tableName, [
            'meta_tag_name',
            'meta_tag_http_equiv',
            'meta_tag_default_value',
            'position',
            'created',
            'modified',
        ], [
            [
                'meta_tag_name' => 'title',
                'meta_tag_http_equiv' => '',
                'meta_tag_default_value' => '',
                'position' => 1,
                'created' => $date,
                'modified' => $date,
            ],
            [
                'meta_tag_name' => 'keywords',
                'meta_tag_http_equiv' => '',
                'meta_tag_default_value' => '',
                'position' => 2,
                'created' => $date,
                'modified' => $date,
            ],
            [
                'meta_tag_name' => 'description',
                'meta_tag_http_equiv' => '',
                'meta_tag_default_value' => '',
                'position' => 3,
                'created' => $date,
                'modified' => $date,
            ],
        ]);
    }

    /**
    * commands will be executed in transaction
    */
    public function safeDown()
    {
        $this->dropTable($this->relatedTableName);
        $this->dropTable($this->tableName);
    }
}
