<?php

/* @var $user \common\entities\User */

$confirmLink = Yii::$app->urlManager->createAbsoluteUrl(['site/signup-confirm', 'token' => $user->email_confirm_token]);
?>
    <?= $user->username ?>,

    Для подтверждения аккаунта перейдите по ссылке:

<?= $confirmLink ?>