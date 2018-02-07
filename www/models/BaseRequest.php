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
class BaseRequest extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%request}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'second_user_id', 'message_id', 'up_at'], 'default', 'value' => null],
            [['user_id', 'second_user_id', 'message_id', 'up_at'], 'required', 'strict' => true],
            [['user_id', 'second_user_id', 'message_id', 'up_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'second_user_id' => Yii::t('app', 'Second User ID'),
            'message_id' => Yii::t('app', 'Message ID'),
            'up_at' => Yii::t('app', 'Up At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('@Requests');
    }

    /**
     * @inheritdoc
     * @return RequestQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RequestQuery(get_called_class());
    }
}
