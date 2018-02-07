<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Message]].
 *
 * @see Message
 */
class MessageQuery extends \yii\db\ActiveQuery
{
    public function byUsers(int $userId1, int $userId2): self
    {
        return $this->andWhere(['up_user_id' => min($userId1, $userId2), 'down_user_id' => max($userId1, $userId2)]);
    }

    /**
     * @inheritdoc
     * @return Message[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Message|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
