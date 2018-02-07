<?php

namespace app\models;

use Yii;
use yii\db\IntegrityException;

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
class Online extends BaseOnline
{
    use ProfileTrait;

    const CLEAN_TIME = 7 * 24 * 3600;
    const ONLINE_TIME = 1800;
    const ONLINE_ICON_TIME = 900;
    const UP_UPDATE_TIME = 600;

    public function updateFromProfile(Profile $profile) {
        Yii::configure($this, $profile->getAttributes(['name_id', 'city_id', 'age', 'age_from', 'age_to', 'gender']));
        $virtreal = $profile->virtreal;
        $this->virt = $virtreal == Profile::VIRTREAL_REAL ? Online::VIRT_N : Online::VIRT_Y;
        $this->real = $virtreal == Profile::VIRTREAL_VIRT ? Online::REAL_N : Online::REAL_Y;
        return $this;
    }

    public static function upUser(Profile $profile, $updateProfile = false)
    {
        $model = static::findOne(['user_id' => $profile->user_id]);
        if (!$model) {
            try {
                $model = new static(['user_id' => $profile->user_id, 'up_at' => time()]);
                if (!$model->updateFromProfile($profile)->save()) {
                    Yii::error('Error in updateFromProfile(...)->save() (insert) ' . __METHOD__);
                }
            } catch (IntegrityException $ex) {
                Yii::error('IntegrityException in updateFromProfile(...)->save() (insert) ' . __METHOD__);
            }
        } else {
            if ($updateTime = (time() - $model->up_at) >= self::UP_UPDATE_TIME) {
                $model->up_at = time();
                if (!$updateProfile) {
                    return static::updateAll(['up_at' => $model->up_at], ['user_id' => $model->user_id]);
                }
            }
            if ($updateTime || $updateProfile) {
                if (!$model->updateFromProfile($profile)->save()) {
                    Yii::error('Error in updateFromProfile(...)->save() (update) ' . __METHOD__);
                }
            }
        }
    }

    public function isOnline()
    {
        return (time() - $this->up_at) <= (self::ONLINE_ICON_TIME + self::UP_UPDATE_TIME / 2);
    }

    public function isVirt()
    {
        return $this->virt == Online::VIRT_Y;
    }

    public function isReal()
    {
        return $this->real == Online::REAL_Y;
    }
}
