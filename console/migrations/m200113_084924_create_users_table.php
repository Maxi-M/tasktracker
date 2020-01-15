<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%users}}`.
 */
class m200113_084924_create_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%users}}', [
            'id' => $this->primaryKey(),
            // будем использовать e-mail в качестве уникального username
            'email' => $this->string()->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'created_at' => $this->timestamp()->defaultExpression('now()')->notNull(),
            'updated_at' => $this->timestamp()->defaultExpression('now()')->notNull(),
            'name' => $this->string(100)->notNull(),
            'surname' => $this->string(100),
            'phone' => $this->string(50),
            'photo_id' => $this->integer()->defaultValue(0),
        ], $tableOptions ?? null);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%users}}');
    }
}
