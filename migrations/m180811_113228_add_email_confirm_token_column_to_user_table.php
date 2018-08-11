<?php

use yii\db\Migration;

/**
 * Handles adding email_confirm_token to table `user`.
 */
class m180811_113228_add_email_confirm_token_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('user', 'email_confirm_token', $this->string()->unique()->after('email')->comment('Токен для подтверждения почты'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('user', 'email_confirm_token');
    }
}
