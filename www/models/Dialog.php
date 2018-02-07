<?php

namespace app\models;

use yii\db\Expression;

/**
 * This is the model class for table "{{%dialog}}".
 *
 * @property int $user_id
 * @property int $second_user_id
 * @property int $message_id
 * @property string $type
 * @property int $unread_count
 * @property string $private_comment
 * @property int $created_at
 * @property int $up_at
 *
 * @property User $user
 */
class Dialog extends BaseDialog
{
    public static function addNewMessage(Message $msg, Profile $upProfile, Profile $downProfile): void
    {
        if ($msg->direct === Message::DIRECT_UP) {
            $from = $msg->down_user_id;
            $to = $msg->up_user_id;
        } else {
            $from = $msg->up_user_id;
            $to = $msg->down_user_id;
        }
        if ($from != $to) {
            $attr = [
                'message_id' => new Expression('IF([[message_id]] < :msgId, :msgId, [[message_id]])'),
                'type' => self::TYPE_OUT,
                'up_at' => new Expression('IF([[message_id]] < :msgId, :now, [[up_at]])', [':now' => time()]),
            ];
            $update = static::updateAll($attr, ['user_id' => $from, 'second_user_id' => $to], [':msgId' => $msg->id]);
            if (!$update) {
                $dialog = new static([
                    'user_id' => $from,
                    'second_user_id' => $to,
                    'message_id' => $msg->id,
                    'type' => self::TYPE_OUT,
                    'unread_count' => 0,
                    'up_at' => time(),
                ]);
                if ($dialog->insert()) {
                    static::deleteAll(['user_id' => $from, 'second_user_id' => $to]);
                }
            }
        }
        $attr = [
            'message_id' => new Expression('IF([[message_id]] < :msgId, :msgId, [[message_id]])'),
            'type' => new Expression('IF([[type]] <> :typeBan, :typeOut, [[type]])'),
            'up_at' => new Expression('IF(([[message_id]] < :msgId) AND ([[type]] <> :typeBan), :now, [[up_at]])', [':now' => time()]),
        ];
        if ($msg->read == Message::READ_N) {
            $attr['unread_count'] = new Expression('[[unread_count]] + 1');
        }
        $update = static::updateAll($attr, ['user_id' => $to, 'second_user_id' => $from], [':msgId' => $msg->id, ':typeBan' => self::TYPE_BAN, ':typeOut' => self::TYPE_OUT]);
        if ($update) {
            if ($from != $to && static::find(['user_id' => $to, 'second_user_id' => $from])->select(['type'])->scalar() != self::TYPE_BAN) {
                Profile::updateAllCounters(['dialog_counter' => 1], ['user_id' => $to]);
                Profile::clearProfilesCache($to);
            }
        } else {
            $update = Request::updateAll(['message_id' => $msg->id, 'up_at' => time()], ['user_id' => $to, 'second_user_id' => $from]);
            if (!$update) {
                $dialog = new Request([
                    'user_id' => $to,
                    'second_user_id' => $from,
                    'message_id' => $msg->id,
                    'up_at' => time(),
                ]);
                $dialog->insert();
            }
            Profile::updateAllCounters(['request_counter' => 1], ['user_id' => $to]);
            Profile::clearProfilesCache($to);
        }
    }
}
