<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = Yii::$app->name;
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Форум</h1>

        <p class="lead">Список тем</p>
    </div>

    <div class="body-content">

        <div class="row">
            <?if (!Yii::$app->user->isGuest):?>
                <div class="text-right">
                    <a href="<?= Url::to('/site/create-topic') ?>" class="btn btn-success">+ Тема</a>
                </div>
            <?endif?>

            <?= \yii\grid\GridView::widget([
                'dataProvider' => $dataProvider,
                'showOnEmpty' => false,
                'emptyText' => '',
                'columns' => [
                    [
                        'attribute' => 'name',
                        'format' => 'html',
                        'label' => 'Тема / Автор',
                        'value' => function($model) {
                            return Html::a($model->name, '/site/topic/' . $model->id) . '<br>' . $model->author->username;
                        }
                    ],
                    'answersCount:text:Ответов',
                    [
                        'attribute' => 'lastMessage',
                        'label' => 'Последнее сообщение',
                        'value' => function($model) {
                            return $model->lastMessage->author->username . ' / ' . $model->lastMessage->created_at;
                        }
                    ],
                ],
            ]) ?>
        </div>

    </div>
</div>
