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
class Profile extends BaseProfile
{
    use ProfileTrait;

    const VISIT_UPDATE_TIME = 120;

    /**
     * Finds model by id
     *
     * @param int $id
     * @return static|null
     */
    public static function findById(int $id)
    {
        /* @var $mcache \app\components\MemoryCache */
        $mcache = Yii::$app->mcache;
        $row = $mcache->get($id, __CLASS__);
        if (is_array($row)) {
            $model = static::instantiate($row);
            $modelClass = get_class($model);
            $modelClass::populateRecord($model, $row);
        } elseif ($row === false) {
            $model = static::find()->byId($id)->one();
            $mcache->set($id, __CLASS__, $model ? $model->getAttributes() : null);
        } else {
            $model = null;
        }
        return $model;
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->mcache->set($this->user_id, __CLASS__, $this->getAttributes());
        Online::upUser($this, true);
        parent::afterSave($insert, $changedAttributes);
    }

    public function updateVisit(): self
    {
        $this->visit_at = time();
        static::updateAll(['visit_at' => $this->visit_at], ['user_id' => $this->user_id]);
        Yii::$app->mcache->set($this->user_id, __CLASS__, $this->getAttributes());
        Online::upUser($this);
        return $this;
    }

    public static function lockProfiles(array $ids): array
    {
        sort($ids, SORT_NUMERIC);
        $command = static::find()->andWhere(['user_id' => $ids])->orderBy(['user_id' => SORT_ASC])->createCommand();
        return static::findBySql($command->sql . ' FOR UPDATE', $command->params)->indexBy('user_id')->all();
    }

    public static function clearProfilesCache(int $id): bool
    {
        return Yii::$app->mcache->delete($id, __CLASS__);
    }

    function initFromParam(array $params): bool
    {
        if (in_array('male', $params)) {
            $this->gender = self::GENDER_M;
        } elseif (in_array('female', $params)) {
            $this->gender = self::GENDER_F;
        } else {
            return false;
        }
        if (in_array('findMale', $params)) {
            $this->gender .= self::GENDER_M;
        } elseif (in_array('findFemale', $params)) {
            $this->gender .= self::GENDER_F;
        }
        if (in_array('virt', $params)) {
            $this->virtreal = self::VIRTREAL_VIRT;
        } elseif (in_array('real', $params)) {
            $this->virtreal = self::VIRTREAL_VIRT;
        } elseif (in_array('virt,real', $params)) {
            $this->virtreal = self::VIRTREAL_BOTH;
        }
        return true;
    }

    function getHeaderText(): string
    {
        // getNameFromCache - возвращает безопасный html
        $text = $this->getNameFromCache();
        if ($age = $this->age) {
            $text .= ', ' . $age;
        }
        // getCityFromCache - возвращает безопасный html
        if ($city = $this->getCityFromCache()) {
            $text .= ' (' . $city . ')';
        }
        return $text;
    }

    public function getLastMessages(int $userId, int $lastId = null): array
    {
        $result = ['all' => is_null($lastId), 'lastMsgId' => 0, 'list' => []];
        $userId2 = $this->user_id;
        $directUp = $userId2 <= $userId ? 'in' : 'out';
        $directDown = $userId2 <= $userId ? 'out' : 'in';
        $messagesQuery = Message::find()
            ->byUsers($userId, $userId2)
            ->orderBy(['id' => SORT_DESC])
            ->limit(Message::COUNT_LIMIT + 1);
        if ($lastId) {
            $messagesQuery->andWhere(['>=', 'id', $lastId]);
        }
        $i = 0;
        foreach ($messagesQuery->all() as $msg) {
            if ($i == 0) {
                $result['lastMsgId'] = $msg->id;
            }
            if ($lastId && $lastId == $msg->id) {
                $result['all'] = true;
                break;
            }
            if (++$i > Message::COUNT_LIMIT) {
                $result['all'] = false;
                break;
            }
            $msgItem = [
                'id' => $msg->id,
                'direct' => $msg->direct == Message::DIRECT_UP ? $directUp : $directDown,
                'date' => $msg->created_at,
                'text' => $msg->text,
            ];
            $result['list'][] = $msgItem;
        }
        return $result;
    }
}
