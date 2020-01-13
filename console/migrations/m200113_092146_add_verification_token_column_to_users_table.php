<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%users}}`.
 */
class m200113_092146_add_verification_token_column_to_users_table extends Migration
{
    public function up()
    {
        $this->addColumn('{{%users}}', 'verification_token', $this->string()->defaultValue(null));
    }

    public function down()
    {
        $this->dropColumn('{{%users}}', 'verification_token');
    }
}
