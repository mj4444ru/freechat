<?php

namespace app\components;

use Yii;

class ErrorAction extends \yii\web\ErrorAction
{
    public function run()
    {
        $headers = Yii::$app->getRequest()->getHeaders();
        if ($headers->get('X-Pjax') && explode(' ', $headers->get('X-Pjax-Container'))[0] === '#pjax-container') {
            Yii::$app->getResponse()->setStatusCode(200);
            return $this->renderHtmlResponse();
        }
        return parent::run();
    }
}
