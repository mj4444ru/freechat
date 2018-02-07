<?php

namespace app\models;

use Yii;
use yii\helpers\Html;

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
class Name extends BaseName
{
    public static function getMale()
    {
        static $name = null;
        return $name ?: ($name = Yii::t('app', 'Парень'));
    }

    public static function getFemale()
    {
        static $name = null;
        return $name ?: ($name = Yii::t('app', 'Девушка'));
    }

    public static function getFindMale()
    {
        static $name = null;
        return $name ?: ($name = Yii::t('app', 'парня'));
    }

    public static function getFindFemale()
    {
        static $name = null;
        return $name ?: ($name = Yii::t('app', 'девушку'));
    }

    public static function getFindFriends()
    {
        static $name = null;
        return $name ?: ($name = Yii::t('app', 'друзей'));
    }

    public static function getNameFromCache($id)
    {
        return Yii::$app->mcache->getOrSet($id, __CLASS__, function ($id) {
            $name = Name::find()->byId($id)->select(['name'])->scalar();
            return $name !== false ? Html::encode($name) : null;
        });
    }

    public static function getNamesFromCache($ids)
    {
        return Yii::$app->mcache->multiGetOrSet($ids, __CLASS__, function (array $ids) {
            $results = Name::find()->byId($ids)->select(['name'])->indexBy('id')->column();
            foreach ($results as &$value) {
                $value = Html::encode($value);
            }
            return $results;
        });
    }
}
