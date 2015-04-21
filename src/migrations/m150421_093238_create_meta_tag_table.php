<?php

use yii\db\Schema;

/**
 * Class m150421_093238_create_meta_tag_table migration
 */
class m150421_093238_create_meta_tag_table extends \yii\db\Migration
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
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            $this->tableName,
            [
                'id' => Schema::TYPE_PK,

                'name' => Schema::TYPE_STRING . ' NOT NULL COMMENT "Specifies a name for the metadata"',
                'http_equiv' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Provides an HTTP header for the information/value of the content attribute"',
                'default_value' => Schema::TYPE_TEXT . ' NULL DEFAULT NULL COMMENT "Default value for the meta tag"',
                'description' => Schema::TYPE_STRING . ' NULL DEFAULT NULL COMMENT "Short description for tag"',
                'is_active' => Schema::TYPE_SMALLINT . '(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT "Register or not this tag on the front"',
                'position' => Schema::TYPE_INTEGER . ' UNSIGNED NOT NULL DEFAULT 0',
            ],
            $tableOptions
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
