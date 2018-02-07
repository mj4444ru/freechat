<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%online}}".
 *
 * @property int $user_id
 * @property int $name_id
 * @property int $city_id
 * @property string $gender
 * @property string $virt
 * @property string $real
 * @property resource $tags
 * @property int $age
 * @property int $age_from
 * @property int $age_to
 * @property int $created_at
 * @property int $up_at
 *
 * @property City $city
 * @property Name $name
 * @property User $user
 */
class BaseOnline extends \yii\db\ActiveRecord
{
    const GENDER_M = 'm';
    const GENDER_F = 'f';
    const GENDER_MF = 'mf';
    const GENDER_FM = 'fm';
    const GENDER_MM = 'mm';
    const GENDER_FF = 'ff';
    const VIRT_Y = 'y';
    const VIRT_N = 'n';
    const REAL_Y = 'y';
    const REAL_N = 'n';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%online}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name_id', 'city_id', 'age', 'age_from', 'age_to', 'up_at'], 'default', 'value' => null],
            [['user_id', 'gender', 'virt', 'real', 'up_at'], 'required', 'strict' => true],
            [['user_id', 'name_id', 'city_id', 'age', 'age_from', 'age_to', 'up_at'], 'integer'],
            [['gender', 'virt', 'real', 'tags'], 'string'],
            [['gender'], 'in', 'range' => ['m', 'f', 'mf', 'fm', 'mm', 'ff'], 'strict' => true],
            [['virt', 'real'], 'in', 'range' => ['y', 'n'], 'strict' => true],
            [['gender', 'virt', 'real'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => Yii::t('app', 'User ID'),
            'name_id' => Yii::t('app', 'Name ID'),
            'city_id' => Yii::t('app', 'City ID'),
            'gender' => Yii::t('app', 'Gender'),
            'virt' => Yii::t('app', 'Virt'),
            'real' => Yii::t('app', 'Real'),
            'tags' => Yii::t('app', 'Tags'),
            'age' => Yii::t('app', 'Age'),
            'age_from' => Yii::t('app', 'Age From'),
            'age_to' => Yii::t('app', 'Age To'),
            'created_at' => Yii::t('app', 'Created At'),
            'up_at' => Yii::t('app', 'Up At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id'])->inverseOf('@Onlines');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getName()
    {
        return $this->hasOne(Name::className(), ['id' => 'name_id'])->inverseOf('@Onlines');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('@Online');
    }

    /**
     * @inheritdoc
     * @return OnlineQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new OnlineQuery(get_called_class());
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
            'gender' => [
                'm' => Yii::t('app', 'M'),
                'f' => Yii::t('app', 'F'),
                'mf' => Yii::t('app', 'Mf'),
                'fm' => Yii::t('app', 'Fm'),
                'mm' => Yii::t('app', 'Mm'),
                'ff' => Yii::t('app', 'Ff'),
            ],
            'virt' => [
                'y' => Yii::t('app', 'Y'),
                'n' => Yii::t('app', 'N'),
            ],
            'real' => [
                'y' => Yii::t('app', 'Y'),
                'n' => Yii::t('app', 'N'),
            ],
        ];
    }
}
