<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%profile}}".
 *
 * @property int $user_id
 * @property int $name_id
 * @property int $city_id
 * @property string $status
 * @property string $gender
 * @property string $virtreal
 * @property int $age
 * @property int $age_from
 * @property int $age_to
 * @property int $growth
 * @property int $weight
 * @property int $constitution
 * @property int $request_counter
 * @property int $dialog_counter
 * @property int $created_at
 * @property int $updated_at
 * @property int $visit_at
 *
 * @property City $city
 * @property Name $name
 * @property User $user
 */
class BaseProfile extends \yii\db\ActiveRecord
{
    const STATUS_ACTIVE = 'active';
    const STATUS_BLOCKED = 'blocked';
    const GENDER_M = 'm';
    const GENDER_F = 'f';
    const GENDER_MF = 'mf';
    const GENDER_FM = 'fm';
    const GENDER_MM = 'mm';
    const GENDER_FF = 'ff';
    const VIRTREAL_BOTH = 'both';
    const VIRTREAL_VIRT = 'virt';
    const VIRTREAL_REAL = 'real';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%profile}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'name_id', 'city_id', 'age', 'age_from', 'age_to', 'growth', 'weight', 'constitution', 'request_counter', 'dialog_counter', 'visit_at'], 'default', 'value' => null],
            [['user_id', 'status', 'gender', 'virtreal', 'request_counter', 'dialog_counter', 'visit_at'], 'required', 'strict' => true],
            [['user_id', 'name_id', 'city_id', 'age', 'age_from', 'age_to', 'growth', 'weight', 'constitution', 'request_counter', 'dialog_counter', 'visit_at'], 'integer'],
            [['status', 'gender', 'virtreal'], 'string'],
            [['status'], 'in', 'range' => ['active', 'blocked'], 'strict' => true],
            [['gender'], 'in', 'range' => ['m', 'f', 'mf', 'fm', 'mm', 'ff'], 'strict' => true],
            [['virtreal'], 'in', 'range' => ['both', 'virt', 'real'], 'strict' => true],
            [['status', 'gender', 'virtreal'], 'required'],
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
            'status' => Yii::t('app', 'Status'),
            'gender' => Yii::t('app', 'Gender'),
            'virtreal' => Yii::t('app', 'Virtreal'),
            'age' => Yii::t('app', 'Age'),
            'age_from' => Yii::t('app', 'Age From'),
            'age_to' => Yii::t('app', 'Age To'),
            'growth' => Yii::t('app', 'Growth'),
            'weight' => Yii::t('app', 'Weight'),
            'constitution' => Yii::t('app', 'Constitution'),
            'request_counter' => Yii::t('app', 'Request Counter'),
            'dialog_counter' => Yii::t('app', 'Dialog Counter'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'visit_at' => Yii::t('app', 'Visit At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCity()
    {
        return $this->hasOne(City::className(), ['id' => 'city_id'])->inverseOf('@Profiles');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getName()
    {
        return $this->hasOne(Name::className(), ['id' => 'name_id'])->inverseOf('@Profiles');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->inverseOf('@Profile');
    }

    /**
     * @inheritdoc
     * @return ProfileQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProfileQuery(get_called_class());
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
                'updatedAtAttribute' => 'updated_at'
            ]
        ];
    }

    public function attributeEnumLabels()
    {
        return [
            'status' => [
                'active' => Yii::t('app', 'Active'),
                'blocked' => Yii::t('app', 'Blocked'),
            ],
            'gender' => [
                'm' => Yii::t('app', 'M'),
                'f' => Yii::t('app', 'F'),
                'mf' => Yii::t('app', 'Mf'),
                'fm' => Yii::t('app', 'Fm'),
                'mm' => Yii::t('app', 'Mm'),
                'ff' => Yii::t('app', 'Ff'),
            ],
            'virtreal' => [
                'both' => Yii::t('app', 'Both'),
                'virt' => Yii::t('app', 'Virt'),
                'real' => Yii::t('app', 'Real'),
            ],
        ];
    }
}
