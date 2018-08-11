<?php

use app\models\User;
use yii\db\Migration;

/**
 * Class m180811_090246_insert_into_topics_values
 */
class m180811_090246_insert_into_topics_values extends Migration
{
    public $values = [
        [
            'created_at' => '2018-07-27 17:12:00',
            'name' => 'Вирус на сайте',
            'author' => 'user1',
        ],
        [
            'created_at' => '2014-11-08 06:19:00',
            'name' => 'формат apng',
            'author' => 'user2',
        ],
        [
            'created_at' => '2018-07-19 18:05:00',
            'name' => 'Запрет на вход на сайт с определенного браузера.',
            'author' => 'user1',
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        foreach($this->values as $value) {
            $this->insert('topics', [
                'created_at' => $value['created_at'],
                'name' => $value['name'],
                'author_id' => User::findByUsername($value['author'])->id,
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        foreach($this->values as $value) {
            $this->delete('topics', [
                'created_at' => $value['created_at'],
                'name' => $value['name'],
                'author_id' => User::findByUsername($value['author'])->id,
            ]);
        }
    }
}
