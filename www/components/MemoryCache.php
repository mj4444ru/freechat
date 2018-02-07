<?php

namespace app\components;

use Yii;

/**
 * @property-write string $primary ID основного кеша
 */
class MemoryCache extends \yii\base\Object
{
    const DEF_DURATION = 3600;

    /**
     * @var \yii\caching\Cache
     */
    private $cache = null;
    private $items = [];

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

    /**
     * Функция возвращает данные из кеша с промежуточным кешированием в памяти.
     * А так же перезаписывает значение если срок жизни в кеше меньше половины.
     * Функция записывает штамп времени с данными.
     * @param string|int $key
     * @param string $prefix
     * @param int $duration
     * @return mixed
     */
    public function get($key, string $prefix, int $duration = self::DEF_DURATION)
    {
        $key2 = $prefix . '/' . $key;
        if (array_key_exists($key2, $this->items)) {
            return $this->items[$key2];
        }
        $value = $this->cache->get($key2);
        if (is_array($value)) {
            if ((time() - $value[0]) > ($duration >> 1)) {
                $value[0] = time();
                $this->cache->set($key2, $value, $duration);
            }
            $value = $value[1];
        }
        return $this->items[$key2] = $value;
    }

    /**
     * Функция возвращает данные из кеша с промежуточным кешированием в памяти.
     * А так же перезаписывает значение если срок жизни в кеше меньше половины.
     * Функция записывает штамп времени с данными.
     * @param string|int $key
     * @param string $prefix
     * @param callable $callable
     * @param int $duration
     * @return mixed
     */
    public function getOrSet($key, string $prefix, callable $callable, int $duration = self::DEF_DURATION)
    {
        $key2 = $prefix . '/' . $key;
        if (array_key_exists($key2, $this->items)) {
            return $this->items[$key2];
        }
        $value = $this->cache->get($key2);
        if ($value === false) {
            $value = call_user_func($callable, $key, $prefix, $duration);
            if ($value !== false) {
                $this->cache->set($key2, [time(), $value], $duration);
            }
        } elseif (is_array($value)) {
            if ((time() - $value[0]) > ($duration >> 1)) {
                $value[0] = time();
                $this->cache->set($key2, $value, $duration);
            }
            $value = $value[1];
        }
        return $this->items[$key2] = $value;
    }

    /**
     * Функция записывает данные в кеш и обновляет промежуточный кеш в памяти.
     * Функция записывает штамп времени с данными.
     * @param string|int $key
     * @param string $prefix
     * @param mixed $value
     * @param int $duration
     * @return bool
     */
    public function set($key, string $prefix, $value, int $duration = self::DEF_DURATION)
    {
        $key2 = $prefix . '/' . $key;
        $this->items[$key2] = $value;
        return $this->cache->set($key2, [time(), $value], $duration);
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
    public function multiGetOrSet(array $keys, string $prefix, callable $callable, int $duration = self::DEF_DURATION): array
    {
        $results = [];
        $keyMap = [];
        foreach ($keys as $key) {
            $key2 = $prefix . '/' . $key;
            if (array_key_exists($key, $this->items[$key2])) {
                $results[$key] = $this->items[$key2];
            } else {
                $results[$key] = false;
                $keyMap[$key] = $key2;
            }
        }
        if (!$keyMap) {
            return $results;
        }
        $keys = [];
        $updateKeys = [];
        $cacheResult = $this->cache->multiGet($keyMap);
        foreach ($keyMap as $key => $key2) {
            if (($value = $cacheResult[$key2]) === false) {
                $keys[$key2] = $key;
            } else {
                if (is_array($value)) {
                    if ((time() - $value[0]) > ($duration >> 1)) {
                        $value[0] = time();
                        $updateKeys[$key2] = $value;
                    }
                    $value = $value[1];
                }
                $results[$key] = $this->items[$key2] = $value;
            }
        }
        if (!$keys/* || $callable === null*/) {
            if ($updateKeys) {
                $this->cache->multiSet($updateKeys, $duration);
            }
            return $results;
        }
        $readerResult = call_user_func($callable, $keys, $prefix, $duration);
        foreach ($keys as $key2 => $key) {
            $value = isset($readerResult[$key]) ? $readerResult[$key] : false;
            $results[$key] = $this->items[$key2] = $value;
            $updateKeys[$key2] = [time(), $value];
        }
        $this->cache->multiSet($updateKeys, $duration);
        return $results;
    }

    /**
     * Функция удаляет данные из кеша и обновляет промежуточный кеш в памяти.
     * @param string|int $key
     * @param string $prefix
     * @return bool
     */
    public function delete($key, string $prefix): bool
    {
        $key2 = $prefix . '/' . $key;
        unset($this->items[$key2]);
        return $this->cache->delete($key2);
    }
}
