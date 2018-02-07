<?php

namespace app\models;

use Yii;

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
class BaseDialog extends \yii\db\ActiveRecord
{
    const TYPE_SYS = 'sys';
    const TYPE_IN = 'in';
    const TYPE_OUT = 'out';
    const TYPE_HIDDEN = 'hidden';
    const TYPE_BAN = 'ban';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dialog}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'second_user_id', 'message_id', 'unread_count', 'up_at'], 'default', 'value' => null],
            [['user_id', 'second_user_id', 'type', 'unread_count', 'up_at'], 'required', 'strict' => true],
            [['user_id', 'second_user_id', 'message_id', 'unread_count', 'up_at'], 'integer'],
            [['type'], 'string'],
            [['private_comment'], 'string', 'max' => 250],
            [['type'], 'in', 'range' => ['sys', 'in', 'out', 'hidden', 'ban'], 'strict' => true],
            [['type'], 'required'],
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
            'type' => Yii::t('app', 'Type'),
            'unread_count' => Yii::t('app', 'Unread Count'),
            'private_comment' => Yii::t('app', 'Private Comment'),
            'created_at' => Yii::t('app', 'Created At'),
            'up_at' => Yii::t('app', 'Up At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('@Dialogs');
    }

    /**
     * @inheritdoc
     * @return DialogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DialogQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => \yii\behaviors\TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false
            ]
        ];
    }

    public function attributeEnumLabels()
    {
        return [
            'type' => [
                'sys' => Yii::t('app', 'Sys'),
                'in' => Yii::t('app', 'In'),
                'out' => Yii::t('app', 'Out'),
                'hidden' => Yii::t('app', 'Hidden'),
                'ban' => Yii::t('app', 'Ban'),
            ],
        ];
    }
}
