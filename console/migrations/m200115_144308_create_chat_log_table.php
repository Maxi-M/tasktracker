<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%chat_log}}`.
 */
class m200115_144308_create_chat_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%chat_log}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(),
            'created_at' => $this->bigInteger(),
            'message' => $this->text(),
            'type' => $this->tinyInteger(),
        ]);
    }
    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%chat_log}}');
    }
}
