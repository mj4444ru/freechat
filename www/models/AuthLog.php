<?php

namespace app\models;

use yii\db\Expression;

/**
 * This is the model class for table "{{%auth_log}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $ip
 * @property string $login
 * @property string $password
 * @property string $status
 * @property int $created_at
 *
 * @property User $user
 */
class AuthLog extends BaseAuthLog
{
    public static function checkAndLog(int $time, int $limit, array $ipList, string $login, string $password, bool $registration): bool
    {
        if (static::find()->byIp($ipList)->registration($registration)->lastTime($time)->groupBy('ip')->orderBy(['count' => SORT_DESC])->select(new Expression('COUNT(*) AS [[count]]'))->scalar() >= $limit) {
            if (!$registration) {
                foreach ($ipList as $ip) {
                    (new static(['ip' => $ip, 'login' => $login, 'password' => $password, 'status' => self::STATUS_DENIED]))->insert();
                }
            }
            return false;
        }
        return true;
    }

    public static function log(string $status, array $ipList, string $login, string $password, int $userId = null): void
    {
        foreach ($ipList as $ip) {
            (new static(['ip' => $ip, 'login' => $login, 'password' => $password, 'status' => $status, 'user_id' => $userId]))->insert();
        }
    }
}
