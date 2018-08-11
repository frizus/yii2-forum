<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = $topic->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-index">
    <div class="body-content">
        <div class="row">
            <div class="col-xs-12">
                <?if ($topic->isTopicOwner()):?>
                    <div class="text-right">
                        <a href="<?= Url::to('/site/delete-topic/' . $topic->id) ?>" class="btn btn-danger">Удалить тему</a>
                    </div>
                <?endif?>

                <?php
                foreach($posts as  $post)
                {
                    echo '<div class="row" id="post-' . $post->id . '">';
                    echo '<div class="col-xs-4 col-sm-3 col-md-2">';
                    echo '<p class="lead">' . $post->author->username . '</p>';
                    echo '</div>';

                    echo '<div class="col-xs-8 col-sm-9 col-md-10">';
                    echo '<p class="text-muted">' . $post->created_at . '</p>';
                    echo '<div class="well well-sm">';
                    echo $post->text;
                    if ($post->isPostOwner() && !$post->isFirstPost())
                    {
                        echo '<div class="text-right">';
                        echo Html::a('Удалить', '/site/delete-post/' . $post->id, ['class' => 'btn btn-danger btn-sm']);
                        echo '</div>';
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';

                    if ($post->isFirstPost())
                    {
                        echo '<hr>';
                    }
                }
                ?>

                <?if (!Yii::$app->user->isGuest): ?>
                    <?= $this->render('_create-post', ['model' => $postForm]) ?>
                <?endif?>

            </div>
        </div>

    </div>
</div>
