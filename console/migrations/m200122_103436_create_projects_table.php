<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%projects}}`.
 */
class m200122_103436_create_projects_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%projects}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text(),
            'status_id' => $this->integer()->notNull(),
            'parent_id' => $this->integer(),
            'created_at' => $this->timestamp()->defaultExpression('now()')->notNull(),
            'updated_at' => $this->timestamp()->defaultExpression('now()')->notNull(),
            'leader_id' => $this->integer()->notNull(),
        ]);

        $this->addForeignKey('fk_projects_status_id', '{{%projects}}', 'status_id', '{{%status}}', 'id');
        $this->createIndex('idx_projects_status_id', '{{%projects}}', 'status_id');

        $this->addForeignKey('fk_projects_parent_id', '{{%projects}}', 'parent_id', '{{%projects}}', 'id');
        $this->createIndex('idx_projects_parent_id', '{{%projects}}', 'parent_id');

        $this->addForeignKey('fk_projects_leader_id', '{{%projects}}', 'leader_id', '{{%users}}', 'id');
        $this->createIndex('idx_projects_leader_id', '{{%projects}}', 'leader_id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_projects_status_id', '{{%projects}}');
        $this->dropIndex('idx_projects_status_id', '{{%projects}}');

        $this->dropForeignKey('fk_projects_parent_id', '{{%projects}}');
        $this->dropIndex('idx_projects_parent_id', '{{%projects}}');

        $this->dropForeignKey('fk_projects_leader_id', '{{%projects}}');
        $this->dropIndex('idx_projects_leader_id', '{{%projects}}');

        $this->dropTable('{{%projects}}');
    }
}
