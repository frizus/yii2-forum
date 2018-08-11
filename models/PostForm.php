<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * Topic form
 */
class PostForm extends Model
{
    public $topic_id;
    public $text;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['text', 'topic_id'], 'required'],
            [['topic_id'], 'exist', 'targetClass' => 'app\models\Topics', 'targetAttribute' => 'id'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'text' => 'Сообщение',
        ];
    }

    /**
     * @return Posts
     */
    public function createPost()
    {
        if (!$this->validate())
        {
            return false;
        }

        $post = new Posts();
        $post->topic_id = $this->topic_id;
        $post->text = $this->text;
        $post->author_id = Yii::$app->user->id;

        return $post->save() ? $post : null;
    }
}