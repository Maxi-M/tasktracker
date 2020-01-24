<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%project_assignments}}`.
 */
class m200122_125509_create_project_assignments_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%project_assignments}}', [
            'project_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ]);

        $this->addPrimaryKey('pk_project_assignments', '{{%project_assignments}}', ['project_id', 'user_id']);
        $this->addForeignKey('fk_project_assignments_project_id', '{{%project_assignments}}', 'project_id', '{{%projects}}', 'id');
        $this->addForeignKey('fk_project_assignments_user_id', '{{%project_assignments}}', 'user_id', '{{%users}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_project_assignments_project_id', '{{%project_assignments}}');
        $this->dropForeignKey('fk_project_assignments_user_id', '{{%project_assignments}}');
        $this->dropTable('{{%project_assignments}}');
    }
}
