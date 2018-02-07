<?php

namespace app\models;

use Yii;

trait ProfileTrait
{
    public function getGenderText()
    {
        $nameId = $this->name_id;
        return $this->isFemale() ? Name::getFemale() : Name::getMale();
    }

    public function getNameFromCache()
    {
        $nameId = $this->name_id;
        $name = $nameId ? Name::getNameFromCache($nameId) : null;
        return $name ? $name : ($this->isFemale() ? Name::getFemale() : Name::getMale());
    }

    public function getCityFromCache()
    {
        $cityId = $this->city_id;
        return $cityId ? City::getNameFromCache($cityId) : null;
    }

    public function getFindWho($text = true)
    {
        $gender = $this->gender;
        if (in_array($gender, [self::GENDER_MM, self::GENDER_FM])) {
            return $text ? Name::getFindMale() : self::GENDER_M;
        }
        if (in_array($gender, [self::GENDER_MF, self::GENDER_FF])) {
            return $text ? Name::getFindFemale() : self::GENDER_F;
        }
        return $text ? Name::getFindFriends() : false;
    }

    public static function getWordFormRu($n, $word1, $word2, $word3)
    {
        // $word1 - для числа *1,
        // $word2 - для *2-*4,
        // $word5 - для чисел *5-*9, *0, *11-*19
        // getWordFormRu($y, 'год', 'года', 'лет')
        // getWordFormRu($m, 'месяц', 'месяца', 'месяцев')
        // getWordFormRu($d, 'день', 'дня', 'дней')
        if (($n % 100 > 10) && ($n % 100 < 20) || ($n % 10 == 0) || ($n % 10 > 4)) {
            return $word3;
        } elseif (($n % 10 > 1) && ($n % 10 < 5)) {
            return $word2;
        }
        return $word1;
    }

    public function isOnline()
    {
        return (time() - $this->visit_at) <= Online::ONLINE_ICON_TIME;
    }

    public function isVirt()
    {
        return $this->virtreal != self::VIRTREAL_REAL;
    }

    public function isReal()
    {
        return $this->virtreal != self::VIRTREAL_VIRT;
    }

    public function isMale()
    {
        return in_array($this->gender, [self::GENDER_M, self::GENDER_MF, self::GENDER_MM]);
    }

    public function isFemale()
    {
        return in_array($this->gender,[ self::GENDER_F, self::GENDER_FM, self::GENDER_FF]);
    }
}
