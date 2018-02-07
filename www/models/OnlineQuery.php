<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[Online]].
 *
 * @see Online
 */
class OnlineQuery extends \yii\db\ActiveQuery
{
    public function onlineVirt()
    {
        return $this->andWhere(['and', ['>=', 'up_at', time() - Online::ONLINE_TIME], ['virt' => Online::VIRT_Y]]);
    }

    /**
     * @inheritdoc
     * @return Online[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Online|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
