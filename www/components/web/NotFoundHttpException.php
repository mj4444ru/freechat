<?php

namespace app\components\web;

use Yii;

class NotFoundHttpException extends \yii\web\NotFoundHttpException
{
    public function __construct()
    {
        parent::__construct(Yii::t('yii', 'Page not found.'));
    }
}
