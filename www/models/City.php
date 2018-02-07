<?php

namespace app\models;

use yii\helpers\Html;

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
class City extends BaseCity
{
    public static function top($count = 10)
    {
        return [
            10 => 'Москва',
            11 => 'Екатеринбург',
            12 => 'Казань',
            13 => 'Самара',
            14 => 'Ульяновск',
            15 => 'Волгоград',
            16 => 'Оренбург',
            17 => 'Владивосток',
            18 => 'Пенза',
            19 => 'Каспийск',
        ];
    }

    public static function getNameFromCache($id)
    {
        return Yii::$app->mcache->getOrSet($id, __CLASS__, function ($id) {
            $name = City::find()->byId($id)->select(['name', 'full_name'])->asArray()->one();
            return $name ? [Html::encode($name['name']), Html::encode($name['full_name'])] : [null, null];
        });
    }

    public static function getNamesFromCache($ids)
    {
        return Yii::$app->mcache->multiGetOrSet($ids, __CLASS__, function (array $ids) {
            $dbResult = static::find()->byId($ids)->select(['id', 'name', 'full_name'])->indexBy('id')->asArray()->all();
            $results = [];
            foreach ($ids as $id) {
                $results = isset($dbResult[$id]) ? [Html::encode($dbResult[$id]['name']), Html::encode($dbResult[$id]['full_name'])] : [null, null];
            }
            return $results;

        });
    }
}
