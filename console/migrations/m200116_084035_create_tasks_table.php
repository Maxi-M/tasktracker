<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tasks}}`.
 */
class m200116_084035_create_tasks_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tasks}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'created_at' => $this->timestamp()->defaultExpression('now()')->notNull(),
            'updated_at' => $this->timestamp()->defaultExpression('now()')->notNull(),
            'due_at' => $this->timestamp(),
            'author_id' => $this->integer()->notNull(),
            'responsible_id' => $this->integer()->notNull(),
        ]);

        // Добавим ключи
        $this->addForeignKey('fk_tasks_users_author','{{%tasks}}', 'author_id', '{{%users}}', 'id');
        $this->addForeignKey('fk_tasks_users_responsible','{{%tasks}}', 'responsible_id', '{{%users}}', 'id');

        // Индексы
        $this->createIndex('idx_tasks_author_id', '{{%tasks}}', 'author_id');
        $this->createIndex('idx_tasks_responsible_id', '{{%tasks}}', 'responsible_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_tasks_users_author','{{%tasks}}');
        $this->dropForeignKey('fk_tasks_users_responsible','{{%tasks}}');
        $this->dropTable('{{%tasks}}');
    }
}
