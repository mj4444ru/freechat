<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%city}}".
 *
 * @property int $id
 * @property string $name
 * @property string $full_name
 * @property string $nearby
 * @property string $lat
 * @property string $lng
 * @property int $created_at
 *
 * @property Online[] $onlines
 * @property Profile[] $profiles
 */
class BaseCity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%city}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'full_name', 'lat', 'lng'], 'required', 'strict' => true],
            [['lat', 'lng'], 'number'],
            [['name'], 'string', 'max' => 30],
            [['full_name', 'nearby'], 'string', 'max' => 200],
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
            'full_name' => Yii::t('app', 'Full Name'),
            'nearby' => Yii::t('app', 'Nearby'),
            'lat' => Yii::t('app', 'Lat'),
            'lng' => Yii::t('app', 'Lng'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOnlines()
    {
        return $this->hasMany(Online::className(), ['city_id' => 'id'])->inverseOf('city');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProfiles()
    {
        return $this->hasMany(Profile::className(), ['city_id' => 'id'])->inverseOf('city');
    }

    /**
     * @inheritdoc
     * @return CityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CityQuery(get_called_class());
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
