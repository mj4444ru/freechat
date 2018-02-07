<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[AuthLog]].
 *
 * @see AuthLog
 */
class AuthLogQuery extends \yii\db\ActiveQuery
{
    public function byIp($ip): self
    {
        return $this->andWhere(['ip' => $ip]);
    }

    public function registration(bool $value): self
    {
        return $this->andWhere($value ? '[[status]] = :status' : '[[status]] <> :status', [':status' => AuthLog::STATUS_REGISTER]);
    }

    public function lastTime(int $sec): self
    {
        return $this->andWhere('[[created_at]] > :time', [':time' => time() - $sec]);
    }

    /**
     * @inheritdoc
     * @return AuthLog[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return AuthLog|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
