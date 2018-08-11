<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "topics".
 *
 * @property int $id
 * @property string $created_at Дата создания
 * @property string $name Название темы
 * @property int $author_id Автор
 *
 * @property Posts[] $posts
 * @property User $author
 * @property int|string $answersCount
 * @property array|null|\yii\db\ActiveRecord $lastMessage
 * @property array|null|\yii\db\ActiveRecord $topicMessage
 * @property bool $isTopicOwner
 */
class Topics extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'topics';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at'], 'safe'],
            [['author_id'], 'required'],
            [['author_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['author_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author_id' => 'id']],
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
            'name' => 'Название темы',
            'author_id' => 'Автор',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPosts()
    {
        return $this->hasMany(Posts::className(), ['topic_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor()
    {
        return $this->hasOne(User::className(), ['id' => 'author_id']);
    }

    /**
     * @return int|string
     */
    public function getAnswersCount()
    {
        return $this->hasMany(Posts::className(), ['topic_id' => 'id'])->count() - 1;
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getLastMessage()
    {
        return $this->hasMany(Posts::className(), ['topic_id' => 'id'])->orderBy(['created_at' => SORT_DESC])->with(['author'])->one();
    }

    /**
     * @return array|null|\yii\db\ActiveRecord
     */
    public function getTopicMessage()
    {
        return $this->hasMany(Posts::className(), ['topic_id' => 'id'])->orderBy(['created_at' => SORT_ASC])->with(['author'])->one();
    }

    /**
     * @return bool
     */
    public function isTopicOwner()
    {
        return $this->author_id == \Yii::$app->user->id;
    }
}
