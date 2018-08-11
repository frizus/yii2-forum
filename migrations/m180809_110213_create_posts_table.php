<?php

use yii\db\Migration;

/**
 * Handles the creation of table `posts`.
 * Has foreign keys to the tables:
 *
 * - `topics`
 * - `user`
 */
class m180809_110213_create_posts_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('posts', [
            'id' => $this->primaryKey(),
            'created_at' => $this->timestamp()->notNull()->comment('Дата создания'),
            'topic_id' => $this->integer()->notNull()->comment('Тема'),
            'text' => $this->text()->notNull()->comment('Сообщение'),
            'author_id' => $this->integer()->notNull()->comment('Автор'),
        ]);

        // creates index for column `topic_id`
        $this->createIndex(
            'idx-posts-topic_id',
            'posts',
            'topic_id'
        );

        // add foreign key for table `topics`
        $this->addForeignKey(
            'fk-posts-topic_id',
            'posts',
            'topic_id',
            'topics',
            'id',
            'CASCADE'
        );

        // creates index for column `author_id`
        $this->createIndex(
            'idx-posts-author_id',
            'posts',
            'author_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-posts-author_id',
            'posts',
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
        // drops foreign key for table `topics`
        $this->dropForeignKey(
            'fk-posts-topic_id',
            'posts'
        );

        // drops index for column `topic_id`
        $this->dropIndex(
            'idx-posts-topic_id',
            'posts'
        );

        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-posts-author_id',
            'posts'
        );

        // drops index for column `author_id`
        $this->dropIndex(
            'idx-posts-author_id',
            'posts'
        );

        $this->dropTable('posts');
    }
}
