<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%name}}".
 *
 * @property int $id
 * @property string $name
 * @property int $created_at
 *
 * @property Online[] $onlines
 * @property Profile[] $profiles
 */
class BaseName extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%name}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required', 'strict' => true],
            [['name'], 'string', 'max' => 30],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOnlines()
    {
        return $this->hasMany(Online::className(), ['name_id' => 'id'])->inverseOf('name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['name_id' => 'id'])->inverseOf('name');
    }

    /**
     * @inheritdoc
     * @return NameQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NameQuery(get_called_class());
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
