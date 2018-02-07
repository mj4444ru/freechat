<?php

namespace app\models;

/**
 * This is the model class for table "{{%message}}".
 *
 * @property int $id
 * @property int $up_user_id
 * @property int $down_user_id
 * @property string $direct
 * @property string $read
 * @property string $delete
 * @property string $text
 * @property int $created_at
 */
class Message extends BaseMessage
{
    const MAX_LEN = 30000;
    const COUNT_LIMIT = 15;

    public function validateTextLen()
    {
        return count($this->text) <= self::MAX_LEN;
    }

    public function createNew(int $fromId, int $toId, string $text): self
    {
        if ($fromId >= $toId) {
            $this->up_user_id = $toId;
            $this->down_user_id = $fromId;
            $this->direct = self::DIRECT_UP;
        } else {
            $this->up_user_id = $fromId;
            $this->down_user_id = $toId;
            $this->direct = self::DIRECT_DOWN;
        }
        $this->read = $fromId == $toId ? self::READ_Y : self::READ_N;
        $this->delete = self::DELETE_NO;
        $this->text = $text;
        return $this;
    }
}
