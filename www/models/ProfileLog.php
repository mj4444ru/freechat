<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%profile_log}}".
 *
 * @property int $user_id
 * @property string $data
 * @property int $created_at
 *
 * @property User $user
 */
class ProfileLog extends BaseProfileLog
{
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), [
            // add additional translations
        ]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return array_merge(parent::rules(), [
            // add additional rules
        ]);
    }

}
