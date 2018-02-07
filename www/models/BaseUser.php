<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $auth_key
 *
 * @property AuthLog[] $authLogs
 * @property Dialog[] $dialogs
 * @property Online $online
 * @property Profile $profile
 * @property ProfileLog[] $profileLogs
 * @property Request[] $requests
 */
class BaseUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password', 'auth_key'], 'required', 'strict' => true],
            [['username', 'password', 'auth_key'], 'string', 'max' => 16],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'password' => Yii::t('app', 'Password'),
            'auth_key' => Yii::t('app', 'Auth Key'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuthLogs()
    {
        return $this->hasMany(AuthLog::className(), ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDialogs()
    {
        return $this->hasMany(Dialog::className(), ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOnline()
    {
        return $this->hasOne(Online::className(), ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfileLogs()
    {
        return $this->hasMany(ProfileLog::className(), ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRequests()
    {
        return $this->hasMany(Request::className(), ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
