<?php

namespace app\models;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\web\Cookie;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property string $auth_key
 *
 * @property AuthLog[] $authLogs
 * @property Dialog[] $dialogs
 * @property Online $online
 * @property Profile $profile
 * @property ProfileLog[] $profileLogs
 * @property Request[] $requests
 */
class User extends BaseUser implements \yii\web\IdentityInterface
{
    const COOKIE_TIME = 31536000;

    /**
     * @return static|null
     */
    public static function current()
    {
        return Yii::$app->getUser()->getIdentity();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        $model = static::findById($id);
        if ($model) {
            $lastUpdate = Yii::$app->session['fcVisitAt'];
            if (!$lastUpdate || (time() - $lastUpdate) >= Profile::VISIT_UPDATE_TIME) {
                $model->updateVisit();
            }
        }
        return $model;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        parent::findIdentityByAccessToken();
        return null;
    }

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
        if ($model) {
            $profile = Profile::findById($id);
            $model->populateRelation('profile', Profile::findById($id));
            if ($profile) {
                $profile->populateRelation('user', $model);
            } else {
                $model = null;
            }
        }
        return $model;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        if (is_numeric($username)) {
            return static::findById($username);
        }
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey(): string
    {
        return strtr(base64_encode($this->auth_key), '+/', '_-');
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey): bool
    {
        return strtr(base64_encode($this->auth_key), '+/', '_-') === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword(string $password): bool
    {
        return $this->password === $this->passwordHash($password);
    }

    public function setPassword(string $password): string
    {
        return $this->password = $this->passwordHash($password);
    }

    public function afterSave($insert, $changedAttributes)
    {
        Yii::$app->mcache->set($this->id, __CLASS__, $this->getAttributes());
        parent::afterSave($insert, $changedAttributes);
    }

    public function beforeValidate(): bool
    {
        if (!$this->auth_key && $this->isNewRecord) {
            $this->auth_key = Yii::$app->security->generateRandomKey(16);
        }
        return parent::beforeValidate();
    }

    public function login(string $password): self
    {
        Yii::$app->user->login($this, self::COOKIE_TIME);
        $pwddata = Yii::$app->security->encryptByKey($password, self::getAppParam('User.PasswordCryptKey'));
        $cookie = new Cookie(['name' => 'pwddata', 'value' => $pwddata, 'expire' => time() + self::COOKIE_TIME]);
        Yii::$app->response->cookies->add($cookie);
        $this->updateVisit();
        return $this;
    }

    public function logout(): bool
    {
        Yii::$app->response->cookies->remove('pwddata');
        return Yii::$app->user->logout();
    }

    public function register(Profile $profile, string $password): bool
    {
        $user = $this;
        $user->status = User::STATUS_ACTIVE;
        $user->password = md5('*', true);
        $profile->request_counter = 0;
        $profile->dialog_counter = 0;
        $profile->admin_counter = 0;
        $profile->visit_at = time();
        $logMethod = __METHOD__;
        $result = User::getDb()->transaction(function(Connection $db) use ($user, $profile, $password, $logMethod) {
            if ($user->save() !== true) {
                Yii::error('$user->insert() !== true', $logMethod);
                $db->getTransaction()->rollBack();
                return false;
            }
            $user->setPassword($password);
            if ($user->save() !== true) {
                Yii::error('$user->update() !== true', $logMethod);
                $db->getTransaction()->rollBack();
                return false;
            }
            $profile->user_id = $user->id;
            if ($profile->save() !== true) {
                Yii::error('$user->insert() !== true', $logMethod);
                $db->getTransaction()->rollBack();
                return false;
            }
            Profile::clearProfilesCache($profile->user_id);
            return true;
        });
        if ($result) {
            $user->login($password);
        }
        return $result;
    }

    private static function getAppParam(string $name)
    {
        $value = isset(Yii::$app->params[$name]) ? Yii::$app->params[$name] : '';
        if (!$value) {
            throw new InvalidConfigException(sprintf('Bad config param "%s".', $name));
        }
        return $value;
    }

    private function passwordHash(string $password): string
    {
        $sals = self::getAppParam('User.PasswordSalt');
        return md5($this->id . md5($sals . md5(md5($sals . $password) . $sals, true) . $sals), true);
    }

    public function getLogin(): string
    {
        $login = $this->username;
        return $login ?: $this->id;
    }

    public function getPwd(): string
    {
        $pwddata = Yii::$app->getRequest()->getCookies()->getValue('pwddata');
        return $pwddata ? Yii::$app->security->decryptByKey($pwddata, self::getAppParam('User.PasswordCryptKey')) : '[скрыто]';
    }

    public function updateVisit()
    {
        $this->profile->updateVisit();
        Yii::$app->session['fcVisitAt'] = time();
    }

    public function getUserInfo(bool $afterLogin = false): array
    {
        $result = [];
        if ($afterLogin) {
            $result['id'] = $this->id;
        }
        return $result;
    }
}
