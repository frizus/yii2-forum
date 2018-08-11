<?php

use app\models\User;
use yii\db\Migration;

/**
 * Class m180811_085745_insert_into_user_values
 */
class m180811_085745_insert_into_user_values extends Migration
{
    public $values = [
        [
            'username' => 'user1',
            'email' => 'user1@example.com',
            'status' => User::STATUS_ACTIVE,
            'password' => 'password',
        ],
        [
            'username' => 'user2',
            'email' => 'user2@example.com',
            'status' => User::STATUS_ACTIVE,
            'password' => 'password',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach($this->values as $value) {
            $user = new User();
            $user->username = $value['username'];
            $user->email = $value['email'];
            $user->status = $value['status'];
            $user->generateAuthKey();
            $user->generatePasswordResetToken();
            $user->setPassword($value['password']);
            $user->save();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        foreach($this->values as $value) {
            if (($user = User::findByUsername($value['username'])) !== null)
            {
                $user->delete();
            }
        }
    }
}
