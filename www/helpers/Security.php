<?php

namespace app\helpers;

use Yii;
use app\models\AuthLog;
use app\models\User;

class Security
{
    private static $_httpIpList;

    public static function getNoGetPageContent(): string
    {
        return '<!--' . str_repeat('    ', 1024) . '-->';
    }

    public static function getHttpIpList(): array
    {
        if (is_array($this->_httpIpList)) {
            return $this->_httpIpList;
        }
        $userIp = Yii::$app->getRequest()->getUserIP();
        $userIpList = [$userIp => $userIp];
        foreach (['REMOTE_ADDR', 'X_FORWARDER_FOR', 'X_REAL_IP'] as $key) {
            $ip = filter_input(INPUT_SERVER, $key);
            if ($ip) {
                $userIpList[$ip] = $ip;
            }
        }
        return $this->_httpIpList = $userIpList;
    }

    public static function ipAuthCheck(string $login, string $password, array $userIpList, bool $registration = false)
    {
        if (!AuthLog::checkAndLog(3600 * 24, $registration ? 10 : 100, $userIpList, $login, $password, $registration)) {
            return Yii::t('app', 'Ваш IP адрес заблокирован на 24 часа');
        }
        if (!AuthLog::checkAndLog(3600, $registration ? 3 : 50, $userIpList, $login, $password, $registration)) {
            return Yii::t('app', 'Ваш IP адрес заблокирован на 1 час');
        }
        if (!AuthLog::checkAndLog(60, $registration ? 1 : 10, $userIpList, $login, $password, $registration)) {
            return Yii::t('app', 'Ваш IP адрес заблокирован на 1 минуту');
        }
        if (!AuthLog::checkAndLog(10, $registration ? 1 : 2, $userIpList, $login, $password, $registration)) {
            return Yii::t('app', 'Ваш IP адрес заблокирован на 10 секунд');
        }
        return false;
    }

    public static function userLogin(string $login, string $password, array $userIpList, bool $validatePassword, User $user = null): bool
    {
        if (!$user) {
            AuthLog::log(AuthLog::STATUS_LOGIN, $userIpList, $login, $password);
            return false;
        }
        if (!$validatePassword) {
            AuthLog::log(AuthLog::STATUS_PASSWORD, $userIpList, $login, $password, $user->id);
            return false;
        }
        $status = $user->status == User::STATUS_BLOCKED ? AuthLog::STATUS_BLOCKED : AuthLog::STATUS_SUCCESS;
        AuthLog::log($status, $userIpList, $login, '*', $user->id);
        $user->login($password);
        return true;
    }

    public static function userRegister(array $userIpList, $post): bool
    {
        $profile = new Profile();
        if (!$profile->initFromParam($post['params'])) {
            return false;
        }
        $user = new User();
        if ($user->register($profile, rand(10000000, 99999999)) !== true || !is_object(User::current())) {
            return false;
        }
        AuthLog::log(AuthLog::STATUS_REGISTER, $userIpList, $user->id, '*');
        return true;
    }
}
