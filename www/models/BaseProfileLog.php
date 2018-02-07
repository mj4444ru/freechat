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
class BaseProfileLog extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'default', 'value' => null],
            [['user_id', 'data'], 'required', 'strict' => true],
            [['user_id'], 'integer'],
            [['data'], 'string', 'max' => 250],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('@ProfileLogs');
    }

    /**
     * @inheritdoc
     * @return ProfileLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProfileLogQuery(get_called_class());
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
}
