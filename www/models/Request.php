<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%request}}".
 *
 * @property int $user_id
 * @property int $second_user_id
 * @property int $message_id
 * @property int $up_at
 *
 * @property User $user
 */
class Request extends BaseRequest
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
