<?php

use yii\db\Migration;
use yii\db\Schema;

class m181011_220556_create_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%smsbat_message}}', [
            'id' => Schema::TYPE_PK,
            'date_sent' => Schema::TYPE_TIMESTAMP . ' NOT NULL DEFAULT CURRENT_TIMESTAMP',
            'text' => Schema::TYPE_TEXT,
            'phone' => Schema::TYPE_STRING,
            'status' => Schema::TYPE_SMALLINT,
            'message_id' => Schema::TYPE_STRING
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%smsbat_message}}');
    }
}
