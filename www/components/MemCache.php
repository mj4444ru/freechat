<?php

namespace app\components;

class MemCache extends \yii\caching\MemCache
{
    public function buildKey($key): string
    {
        return $this->keyPrefix . md5(is_string($key) ? $key : json_encode($key));
    }
}
