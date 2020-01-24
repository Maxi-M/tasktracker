<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%chat_log}}`.
 */
class m200124_085144_add_project_id_task_id_created_at_columns_to_chat_log_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%chat_log}}', 'project_id', $this->integer());
        $this->addColumn('{{%chat_log}}', 'task_id', $this->integer());

        $this->addForeignKey('fk_chat_log_project_id', '{{%chat_log}}', 'project_id', '{{%projects}}', 'id');
        $this->addForeignKey('fk_chat_log_task_id', '{{%chat_log}}', 'task_id', '{{%tasks}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk_chat_log_project_id', '{{%chat_log}}');
        $this->dropForeignKey('fk_chat_log_task_id', '{{%chat_log}}');

        $this->dropColumn('{{%chat_log}}', 'project_id');
        $this->dropColumn('{{%chat_log}}', 'task_id');
    }
}
