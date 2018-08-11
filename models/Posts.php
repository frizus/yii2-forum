<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property string $created_at Дата создания
 * @property int $topic_id Тема
 * @property string $text Сообщение
 * @property int $author_id Автор
 *
 * @property User $author
 * @property Topics $topic
 * @property bool $isPostOwner
 */
class Posts extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['topic_id', 'text', 'author_id'], 'required'],
            [['topic_id', 'author_id'], 'integer'],
            [['text'], 'string'],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
            [['topic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Topics::className(), 'targetAttribute' => ['topic_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата создания',
            'topic_id' => 'Тема',
            'text' => 'Сообщение',
            'author_id' => 'Автор',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTopic()
    {
        return $this->hasOne(Topics::className(), ['id' => 'topic_id']);
    }

    public function isFirstPost()
    {
        return $this->topic->topicMessage->id == $this->id;
    }

    /**
     * @return bool
     */
    public function isPostOwner()
    {
        return $this->author_id == \Yii::$app->user->id;
    }
}
