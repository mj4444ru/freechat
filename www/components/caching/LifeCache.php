<?php

namespace app\components\caching;

use Yii;
use yii\caching\CacheInterface;

/**
 * @property-write string $primary ID основного кеша
 */
class LifeCache extends \yii\base\BaseObject implements CacheInterface {
    /**
     * @var int
     */
    public $defaultDuration = 3600;
    /**
     * @var CacheInterface
     */
    private $cache = null;

    public function init()
    {
        parent::init();
        if (!$this->cache) {
            $this->setPrimary('cache');
        }
    }

    public function setPrimary(string $id)
    {
        $this->cache = Yii::$app->get($id);
    }

    public function buildKey($key)
    {
        return $this->cache->buildKey($key);
    }

    /**
     * Функция возвращает данные из кеша.
     * А так же перезаписывает значение если срок жизни в кеше меньше половины.
     * Функция записывает штамп времени с данными.
     * @param string|int $key
     * @param int $duration
     * @param string|null $prefix
     * @return mixed
     */
    public function get($key, int $duration = null, string $prefix = null)
    {
        $key = $prefix ? "{$prefix}/{$key}" : $key;
        $duration = $duration ?: $this->defaultDuration;
        $value = $this->cache->get($key);
        if (!is_array($value) || count($value) != 2) {
            return false;
        }
        if (($value[0] - time()) < ($duration >> 1)) {
            $value[0] = time() + $duration;
            $this->cache->set($key, $value, $duration);
        }
        return $value[1];
    }

    /**
     * Функция удаляет данные из кеша.
     * @param string|int $key
     * @param string|null $prefix
     * @return bool
     */
    public function exists($key, string $prefix = null)
    {
        return $this->cache->exists($prefix ? "{$prefix}/{$key}" : $key);
    }

    /**
     * Функция возвращает данные из кеша.
     * А так же перезаписывает значения если срок жизни в кеше меньше половины.
     * Функция записывает штамп времени с данными.
     * @param string[]|int[] $keys
     * @param int $duration
     * @param string $prefix
     * @return array
     */
    public function multiGet($keys, int $duration = null, string $prefix = null)
    {
        $duration = $duration ?: $this->defaultDuration;
        if ($prefix) {
            $keyMap = [];
            foreach ($keys as $key) {
                $keyMap[$key] = "{$prefix}/{$key}";
            }
            $_values = $this->cache->multiGet($keyMap);
            $values = [];
            foreach ($keyMap as $key => $key2) {
                $values[$key] = array_key_exists($key2, $_values) ? $_values[$key2] : false;
            }
        } else {
            $values = $this->cache->multiGet($keys);
        }
        $_values = [];
        foreach ($values as $key => &$value) {
            if (!is_array($value) || count($value) != 2) {
                $value = false;
            } else {
                if (($value[0] - time()) > ($duration >> 1)) {
                    $value[0] = time() + $duration;
                    $_values[$prefix ? $keyMap[$key] : $key] = $value;
                }
                $value = $value[1];
            }
        }
        if ($_values) {
            $values = $this->cache->multiSet($_values, $duration, null);
        }
        return $values;
    }

    /**
     * Функция записывает данные в кеш.
     * Функция записывает штамп времени с данными.
     * @param string|int $key
     * @param mixed $value
     * @param int $duration
     * @param Dependency $dependency
     * @param string|null $prefix
     * @return bool
     */
    public function set($key, $value, $duration = null, $dependency = null, string $prefix = null)
    {
        $key = $prefix ? "{$prefix}/{$key}" : $key;
        $duration = $duration ?: $this->defaultDuration;
        return $this->cache->set($key, [time() + $duration, $value], $duration, null);
    }

    public function multiSet($items, $duration = 0, $dependency = null, string $prefix = null)
    {
        $duration = $duration ?: $this->defaultDuration;
        $values = [];
        foreach ($items as $key => $value) {
            $values[$prefix ? $key : "{$prefix}/{$key}"] = [time() + $duration, $value];
        }
        return $this->cache->multiSet($values, $duration, null);
    }

    /**
     * Функция записывает данные в кеш если их там небыло.
     * Функция записывает штамп времени с данными.
     * @param string|int $key
     * @param mixed $value
     * @param int $duration
     * @param Dependency $dependency
     * @param string|null $prefix
     * @return bool
     */
    public function add($key, $value, $duration = 0, $dependency = null, string $prefix = null)
    {
        $key = $prefix ? "{$prefix}/{$key}" : $key;
        $duration = $duration ?: $this->defaultDuration;
        return $this->cache->add($key, [time() + $duration, $value], $duration, null);
    }

    public function multiAdd($items, $duration = 0, $dependency = null, string $prefix = null)
    {
        $duration = $duration ?: $this->defaultDuration;
        $values = [];
        foreach ($items as $key => $value) {
            $values[$prefix ? $key : "{$prefix}/{$key}"] = [time() + $duration, $value];
        }
        return $this->cache->multiAdd($values, $duration, null);
    }

    /**
     * Функция удаляет данные из кеша и обновляет промежуточный кеш в памяти.
     * @param string|int $key
     * @param string|null $prefix
     * @return bool
     */
    public function delete($key, string $prefix = null)
    {
        return $this->cache->delete($prefix ? $key : "{$prefix}/{$key}");
    }

    public function flush()
    {
        return $this->cache->flush();
    }

    /**
     * Функция возвращает данные из кеша.
     * А так же перезаписывает значение если срок жизни в кеше меньше половины.
     * Функция записывает штамп времени с данными.
     * @param string|int $key
     * @param callable $callable
     * @param int $duration
     * @param Dependency $dependency
     * @param string|null $prefix
     * @return mixed
     */
    public function getOrSet($key, $callable, $duration = null, $dependency = null, string $prefix = null)
    {
        $key = $prefix ? "{$prefix}/{$key}" : $key;
        $duration = $duration ?: $this->defaultDuration;
        $value = $this->cache->get($key);
        if ($value === false || !is_array($value) || count($value) != 2) {
            $value = call_user_func($callable, $this);
            if ($value !== false) {
                $this->cache->set($key, [time() + $duration, $value], $duration, null);
            }
            return $value;
        }
        if ((time() - $value[0]) > (($duration ?: $this->duration) >> 1)) {
            $value[0] = time();
            $this->cache->set($key, $value, $duration ?: $this->duration, null);
        }
        return $value[1];
    }

    /**
     * Функция возвращает данные из кеша с промежуточным кешированием в памяти.
     * А так же перезаписывает значения если срок жизни в кеше меньше половины.
     * Функция записывает штамп времени с данными.
     * @param string[]|int[] $keys
     * @param string $prefix
     * @param callable $callable
     * @param int $duration
     * @return array
     */
    public function multiGetOrSet(array $keys, callable $callable, int $duration = null, $dependency = null, string $prefix = null): array
    {
        $duration = $duration ?: $this->defaultDuration;
        if ($prefix) {
            $keyMap = [];
            foreach ($keys as $key) {
                $keyMap[$key] = "{$prefix}/{$key}";
            }
            $_values = $this->cache->multiGet($keyMap);
            $values = [];
            foreach ($keyMap as $key => $key2) {
                $values[$key] = array_key_exists($key2, $_values) ? $_values[$key2] : false;
            }
        } else {
            $values = $this->cache->multiGet($keys);
        }
        $_values = [];
        $callableKeys = [];
        foreach ($values as $key => &$value) {
            if (!is_array($value) || count($value) != 2) {
                $value = false;
                $callableKeys[] = $key;
            } else {
                if (($value[0] - time()) > ($duration >> 1)) {
                    $value[0] = time() + $duration;
                    $_values[$prefix ? $keyMap[$key] : $key] = $value;
                }
                $value = $value[1];
            }
        }
        $result = call_user_func($callable, $this, $callableKeys);
        foreach ($callableKeys as $key) {
            if (isset($result[$key]) && $result[$key] !== false) {
                $values[$key] = $result[$key];
                $_values[$prefix ? $keyMap[$key] : $key] = [time() + $duration, $value];
            }
        }
        if ($_values) {
            $values = $this->cache->multiSet($_values, $duration, null);
        }
        return $values;
    }

    public function offsetExists($key)
    {
        return $this->get($key) !== false;
    }

    public function offsetGet($key)
    {
        return $this->get($key);
    }

    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    public function offsetUnset($key)
    {
        $this->delete($key);
    }
}
