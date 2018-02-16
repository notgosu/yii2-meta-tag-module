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
                'id' => $this->primaryKey(),
                'name' => $this->string()->notNull()->comment('Specifies a name for the metadata'),
                'is_http_equiv' => $this->smallInteger(1)->unsigned()->notNull()->defaultValue(0)->comment('Is it HTTP header for the information/value of the content attribute'),
                'default_value' => $this->text()->defaultValue(null)->comment('Default value for the meta tag'),
                'description' => $this->string()->defaultValue(null)->comment('Short description for tag'),
                'is_active' => $this->smallInteger(1)->unsigned()->notNull()->defaultValue(1)->comment('Register or not this tag on the front'),
                'position' => $this->integer()->unsigned()->notNull()->defaultValue(0),
            ],
            $tableOptions
        );

        $this->createIndex('unique_name', $this->tableName, ['name'], true);
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable($this->tableName);
    }
}
