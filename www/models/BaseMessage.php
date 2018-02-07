<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%message}}".
 *
 * @property int $id
 * @property int $up_user_id
 * @property int $down_user_id
 * @property string $direct
 * @property string $read
 * @property string $delete
 * @property string $text
 * @property int $created_at
 */
class BaseMessage extends \yii\db\ActiveRecord
{
    const DIRECT_UP = 'up';
    const DIRECT_DOWN = 'down';
    const READ_Y = 'y';
    const READ_N = 'n';
    const DELETE_NO = 'no';
    const DELETE_UP = 'up';
    const DELETE_DOWN = 'down';
    const DELETE_BOTH = 'both';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%message}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['up_user_id', 'down_user_id'], 'default', 'value' => null],
            [['up_user_id', 'down_user_id', 'direct', 'read', 'delete', 'text'], 'required', 'strict' => true],
            [['up_user_id', 'down_user_id'], 'integer'],
            [['direct', 'read', 'delete', 'text'], 'string'],
            [['direct'], 'in', 'range' => ['up', 'down'], 'strict' => true],
            [['read'], 'in', 'range' => ['y', 'n'], 'strict' => true],
            [['delete'], 'in', 'range' => ['no', 'up', 'down', 'both'], 'strict' => true],
            [['direct', 'read', 'delete'], 'required'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'up_user_id' => Yii::t('app', 'Up User ID'),
            'down_user_id' => Yii::t('app', 'Down User ID'),
            'direct' => Yii::t('app', 'Direct'),
            'read' => Yii::t('app', 'Read'),
            'delete' => Yii::t('app', 'Delete'),
            'text' => Yii::t('app', 'Text'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }

    /**
     * @inheritdoc
     * @return MessageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MessageQuery(get_called_class());
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
            'direct' => [
                'up' => Yii::t('app', 'Up'),
                'down' => Yii::t('app', 'Down'),
            ],
            'read' => [
                'y' => Yii::t('app', 'Y'),
                'n' => Yii::t('app', 'N'),
            ],
            'delete' => [
                'no' => Yii::t('app', 'No'),
                'up' => Yii::t('app', 'Up'),
                'down' => Yii::t('app', 'Down'),
                'both' => Yii::t('app', 'Both'),
            ],
        ];
    }
}
