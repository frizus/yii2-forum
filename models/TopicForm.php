<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Topic form
 */
class TopicForm extends Model
{
    public $name;
    public $text;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'text'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'text' => 'Сообщение',
        ];
    }

    /**
     * @return Topics
     */
    public function createTopic()
    {
        $topic = new Topics();
        $topic->name = $this->name;
        $topic->author_id = Yii::$app->user->id;

        if ($topic->save())
        {
            $post = new Posts();
            $post->topic_id = $topic->id;
            $post->text = $this->text;
            $post->author_id = Yii::$app->user->id;
            if ($post->save())
            {
                return $topic;
            }
        }
    }
}