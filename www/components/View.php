<?php

namespace app\components;

use Yii;
use yii\helpers\Html;

class View extends \yii\web\View
{
    private $_isPjax;


    protected function renderHeadHtml()
    {
        $result = parent::renderHeadHtml();
        if (!empty($result)) {
            $result = '    ' . str_replace("\n", "\n    ", $result) . "\n";
        }
        if ($this->cacheStack) {
            $csrf = $this->renderDynamic('return \yii\helpers\Html::csrfMetaTags();');
        } else {
            $csrf = Html::csrfMetaTags();
        }
        return empty($csrf) ? $result : $result . '    ' . $csrf;
    }

    public function getIsPjax()
    {
        if (is_bool($this->_isPjax)) {
            return $this->_isPjax;
        }
        $headers = Yii::$app->getRequest()->getHeaders();
        return $this->_isPjax = $headers->get('X-Pjax') && explode(' ', $headers->get('X-Pjax-Container'))[0] === '#pjax-container';
    }
}
