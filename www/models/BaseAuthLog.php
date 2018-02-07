<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%auth_log}}".
 *
 * @property int $id
 * @property int $user_id
 * @property string $ip
 * @property string $login
 * @property string $password
 * @property string $status
 * @property int $created_at
 *
 * @property User $user
 */
class BaseAuthLog extends \yii\db\ActiveRecord
{
    const STATUS_SUCCESS = 'success';
    const STATUS_BLOCKED = 'blocked';
    const STATUS_LOGIN = 'login';
    const STATUS_PASSWORD = 'password';
    const STATUS_DENIED = 'denied';
    const STATUS_REGISTER = 'register';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auth_log}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'default', 'value' => null],
            [['user_id'], 'integer'],
            [['ip', 'login', 'password', 'status'], 'required', 'strict' => true],
            [['status'], 'string'],
            [['ip', 'login', 'password'], 'string', 'max' => 100],
            [['status'], 'in', 'range' => ['success', 'blocked', 'login', 'password', 'denied', 'register'], 'strict' => true],
            [['status'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'ip' => Yii::t('app', 'Ip'),
            'login' => Yii::t('app', 'Login'),
            'password' => Yii::t('app', 'Password'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('@AuthLogs');
    }

    /**
     * @inheritdoc
     * @return AuthLogQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AuthLogQuery(get_called_class());
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
            'status' => [
                'success' => Yii::t('app', 'Success'),
                'blocked' => Yii::t('app', 'Blocked'),
                'login' => Yii::t('app', 'Login'),
                'password' => Yii::t('app', 'Password'),
                'denied' => Yii::t('app', 'Denied'),
                'register' => Yii::t('app', 'Register'),
            ],
        ];
    }
}
