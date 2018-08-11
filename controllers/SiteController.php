<?php
namespace app\controllers;

use app\models\Posts;
use app\models\Topics;
use app\models\TopicForm;
use app\models\PostForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\models\LoginForm;
use app\models\SignupForm;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup', 'signup-confirm', 'create-topic', 'delete-topic', 'create-post', 'delete-post'],
                'rules' => [
                    [
                        'actions' => ['signup', 'signup-confirm'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout', 'create-topic', 'delete-topic', 'create-post', 'delete-post'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Topics::find(),
            'sort' => false,
        ]);

        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * @param $id
     * @return string
     */
    public function actionTopic($id)
    {
        $topic = Topics::findOne(['id' => $id]);
        $posts = Posts::find()->where(['topic_id' => $id])->orderBy(['created_at' => SORT_ASC])->with(['topic', 'author'])->all();
        if (!\Yii::$app->user->isGuest)
        {
            $postForm = new PostForm();
            $postForm->topic_id = $topic->id;
        }
        else
        {
            $postForm = null;
        }

        return $this->render('view', ['topic' => $topic, 'posts' => $posts, 'postForm' => $postForm]);
    }

    public function actionCreateTopic()
    {
        $model = new TopicForm();

        if ($model->load(Yii::$app->request->post()))
        {
            if ($topic = $model->createTopic())
            {
                \Yii::$app->session->setFlash('success', 'Тема "' . \Yii::$app->formatter->asText($topic->name) . '" создана.');
                return $this->redirect('/site/topic/' . $topic->id);
            }
            else
            {
                \Yii::$app->session->setFlash('error', 'Ошибка при создании темы.');
            }
        }

        return $this->render('create-topic', ['model' => $model]);
    }

    public function actionDeleteTopic($id)
    {
        $topic = Topics::findOne(['id' => $id]);

        if (!$topic)
        {
            return $this->goHome();
        }

        if ($topic->isTopicOwner())
        {
            if ($topic->delete())
            {
                \Yii::$app->session->setFlash('success', 'Тема "' . \Yii::$app->formatter->asText($topic->name) . '" удалена.');
                return $this->goHome();
            }
            else
            {
                \Yii::$app->session->setFlash('error', 'Не удалось удалить тему.');
            }
        }

        return $this->redirect('/site/topic/' . $topic->id);
    }

    public function actionCreatePost()
    {
        $model = new PostForm();

        if ($model->load(Yii::$app->request->post()))
        {
            if ($post = $model->createPost())
            {
                \Yii::$app->session->setFlash('success', 'Сообщение добавлено.');
                return $this->redirect('/site/topic/' . $model->topic_id . '#post-' . $post->id);
            }
            else
            {
                \Yii::$app->session->setFlash('error', 'Сообщение не удалось создать.');
                return $model->validate(['topic_id']) ? $this->redirect('/site/topic/' . $model->topic_id) : $this->goHome();
            }
        }

        return $this->goHome();
    }

    public function actionDeletePost($id)
    {
        $post = Posts::findOne(['id' => $id]);

        if (!$post)
        {
            return $this->goHome();
        }

        if ($post->isPostOwner() && !$post->isFirstPost())
        {
            if ($post->delete())
            {
                \Yii::$app->session->setFlash('success', 'Сообщение удалено.');
                return $this->redirect('/site/topic/' . $post->topic_id);
            }
            else
            {
                \Yii::$app->session->setFlash('error', 'Не удалось удалить сообщение');
            }
        }

        return $this->redirect('/site/topic/' . $post->topic_id . '#post-' . $post->id);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()))
        {
            if ($user = $model->signup())
            {
                if ($model->sentEmailConfirm($user))
                {
                    \Yii::$app->session->setFlash('success', 'На почту выслано письмо с подтверждением');
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * @param $token
     * @return \yii\web\Response
     */
    public function actionSignupConfirm($token)
    {
        $model = new SignupForm();

        try {
            $model->confirmation($token);
            Yii::$app->session->setFlash('success', 'Регистрация подтверждена.');
        } catch (\Exception $e){
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->goHome();
    }
}