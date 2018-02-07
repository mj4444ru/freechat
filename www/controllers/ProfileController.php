<?php

namespace app\controllers;

use Yii;
use yii\web\ForbiddenHttpException;
use app\models\User;

class ProfileController extends \yii\web\Controller
{
    public function init()
    {
        if (!User::current()) {
            $errorText = Yii::t('app', 'Этот раздел сайта доступен только авторизованным пользователям. Если вы не являетесь пользователем системы, просто начните пользоваться сайтом, логин и пароль будут созданы автоматически.');
//            if ()
            throw new ForbiddenHttpException($errorText);
        }
    }

    public function actionIndex()
    {
//        $user = User::current();
//        $user =
//        if (Yii::$app->request->get('profile') === '') {
//            return $this->runAction('profile');
//        }
        return $this->render('index');
    }

    public function actionAuthInfo()
    {
        return $this->render('auth-info');
    }

    private function authOnly($error = false)
    {
        if (!User::current())
        $request = Yii::$app->getRequest();
        if ($error || !$request->getIsAjax() || !$request->getIsPost()) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
    }
}
