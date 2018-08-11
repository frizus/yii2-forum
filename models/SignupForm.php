<?php
namespace app\models;

use yii\base\Model;
use app\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\app\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'Электронная почта',
            'password' => 'Пароль',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->email_confirm_token = \Yii::$app->security->generateRandomString();
        $user->status = User::STATUS_WAIT;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        return $user->save() ? $user : null;
    }

    public function sentEmailConfirm($user)
    {
        $email = $user->email;

        return \Yii::$app->mailer
            ->compose(
                ['html' => 'user-signup-confirm-html', 'text' => 'user-signup-confirm-text'],
                ['user' => $user])
            ->setTo($email)
            ->setFrom(\Yii::$app->params['adminEmail'])
            ->setSubject('Подтверждение регистрации')
            ->send();
    }

    public function confirmation($token)
    {
        if (empty($token)) {
            throw new \DomainException('Пустой токен для подтверждения.');
        }

        $user = User::findOne(['email_confirm_token' => $token]);
        if (!$user) {
            throw new \DomainException('Пользователь не найден.');
        }

        $user->email_confirm_token = null;
        $user->status = User::STATUS_ACTIVE;
        if (!$user->save()) {
            throw new \RuntimeException('Ошибка сохранения.');
        }

        if (!\Yii::$app->getUser()->login($user)){
            throw new \RuntimeException('Ошибка входа.');
        }
    }
}