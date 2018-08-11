<?php

use yii\db\Migration;

/**
 * Handles the creation of table `topics`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m180809_110209_create_topics_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('topics', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->notNull()->comment('Дата создания'),
            'name' => $this->string()->comment('Название темы'),
            'author_id' => $this->integer()->notNull()->comment('Автор'),
        ]);

        // creates index for column `author_id`
        $this->createIndex(
            'idx-topics-author_id',
            'topics',
            'author_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-topics-author_id',
            'topics',
            'author_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-topics-author_id',
            'topics'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-topics-author_id',
            'topics'
        );

        $this->dropTable('topics');
    }
}
