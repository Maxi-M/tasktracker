<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%user}}`.
 */
class m200113_091800_drop_default_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->dropTable('{{%user}}');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return false;
    }
}
