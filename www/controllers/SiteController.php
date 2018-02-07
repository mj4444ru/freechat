<?php

namespace app\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use yii\filters\PageCache;
use app\models\User;
use app\models\Profile;
use app\helpers\Security;
use app\components\ErrorAction;

class SiteController extends \yii\web\Controller
{
    protected $isGet = false;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = [];
        $request = Yii::$app->getRequest();
        $this->isGet = $request->getIsGet();
        if ($this->isGet) {
            $behaviors['pageCache'] = [
                'class' => PageCache::class,
                'enabled' => !YII_ENV_DEV,
                'variations' => [
                    Yii::$app->language,
                    Yii::$app->getUser()->getIsGuest(),
                    $request->getUrl(),
                    $this->view->getIsPjax(),
                ],
            ];
        }
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
            ],
//            'captcha' => [
//                'class' => 'yii\captcha\CaptchaAction',
//                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
//            ],
        ];
    }

    public function actionIndex()
    {
//        if (Yii::$app->request->get('profile') === '') {
//            return $this->runAction('profile');
//        }
        return $this->renderGet('index');
    }

    public function actionInfo()
    {
        return $this->renderGet('info');
    }

    public function actionUserMenu()
    {
        return $this->renderGet('user-menu');
    }

    public function actionRules()
    {
        return $this->renderGet('rules');
    }

    public function actionModerator()
    {
        return $this->renderGet('plans');
    }

    public function actionAnketa($id)
    {
        $model = is_numeric($id) ? Profile::findById($id) : null;
        if (!$model) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
        return $this->renderGet('anketa', ['model' => $model]);
    }

/*
    public function actionSendMessage()
    {
        $this->ajaxOnly();
        $post = Yii::$app->request->post();
        if (!isset($post['from'], $post['to'], $post['text'], $post['lastMsgId'])) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
        $user = User::current();
        if (!$user || $post['from'] != $user->id || !$post['text']) {
            return $this->asJson(false);
        }
        $message = new Message();
        $message->createNew($post['from'], $post['to'], $post['text']);
        if (!$message->validateTextLen()) {
            return $this->asJson(Yii::t('app', 'Превышена максимальная длинна сообщения'));
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $profiles = Profile::lockProfiles([$message->up_user_id, $message->down_user_id]);
            if (!isset($profiles[$message->up_user_id], $profiles[$message->down_user_id])) {
                $transaction->rollBack();
                return $this->asJson(false);
            }
            $message->save();
            Dialog::addNewMessage($message, $profiles[$message->up_user_id], $profiles[$message->down_user_id]);
            $transaction->commit();
        } catch (Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
        return $this->asJson(['messages' => $user->profile->getLastMessages($post['to'], $post['lastMsgId'])]);
    }
*/

    public function actionLogin($modal = false)
    {
        $user = User::current();
        $request = Yii::$app->request;
        if (!$this->isGet) {
            if ($user) {
                if ($modal) {
                    return $this->asJson([
                        'topMenu' => $this->renderPartial('//layouts/main/header-user'),
                        'userInfo' => $user->getUserInfo(true),
                    ]);
                }
                return $this->redirect(['/site/index']);
            }
            $login = $request->post('login');
            $password = $request->post('password');
            if (!$login || !$password || !is_string($login) || !is_string($password)) {
                return $this->asJson(false);
            }
            $user = User::findByUsername($login);
            $validatePassword = $user ? $user->validatePassword($password) : false;
            $userIpList = Security::getHttpIpList();
            if ($error = Security::ipAuthCheck($login, $validatePassword ? '*' : $password, $userIpList)) {
                return $this->asJson($error);
            }
            if (!Security::userLogin($login, $password, $userIpList, $validatePassword, $user)) {
                return $this->asJson(false);
            }
            if ($modal) {
                return $this->asJson([
                    'topMenu' => $this->renderPartial('//layouts/main/header-user'),
                    'userInfo' => $user->getUserInfo(true),
                ]);
            }
            return $this->redirect(['/site/index']);
        }
        if ($request->isAjax && $request->getQueryParam('fancybox')) {
            return $this->renderPartial('login-form', ['popup' => true]);
        }
        return $user ? $this->redirect(['/site/index']) : $this->render('login');
    }

    public function actionLogout()
    {
        $user = User::current();
        if (Yii::$app->request->isPost) {
            $user->logout();
            return $this->redirect('logout');
        }
        return $this->renderGet($user ? 'logout' : 'logout2');
    }

    public function actionRegister()
    {
        $post = Yii::$app->request->post();
        $this->ajaxOnly(!isset($post['params']) || !is_array($post['params']));
        if ($user = User::current()) {
            return $this->asJson([
                'topMenu' => $this->renderPartial('//layouts/main/header-user'),
                'userInfo' => $user->getUserInfo(true),
            ]);
        }
        $userIpList = Security::getHttpIpList();
        if ($error = Security::ipAuthCheck('*', '*', $userIpList, true)) {
            return $this->asJson($error);
        }
        if (!Security::userRegister($userIpList, $post)) {
            return $this->asJson(false);
        }
        return $this->asJson([
            'topMenu' => $this->renderPartial('//layouts/main/header-user'),
            'userInfo' => User::current()->getUserInfo(true),
        ]);
    }

    private function renderGet($view, $params = [])
    {
        if (!$this->isGet) {
            return $this->renderContent('');
        }
        return $this->render($view, $params);
    }

    private function ajaxOnly($error = false)
    {
        $request = Yii::$app->getRequest();
        if ($error || !$request->getIsAjax() || !$request->getIsPost()) {
            throw new NotFoundHttpException(Yii::t('yii', 'Page not found.'));
        }
    }
}
