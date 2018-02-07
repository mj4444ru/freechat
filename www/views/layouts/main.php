<?php

use yii\helpers\Html;
use app\assets\AppAsset;
use app\models\User;
use app\helpers\Security;

/* @var $this \yii\web\View */
/* @var $content string */

$request = Yii::$app->getRequest();
if (!$request->getIsGet()) {
    echo Security::getNoGetPageContent();
    return;
}

if ($this->getIsPjax()) {
    $this->beginPage();
    $this->head();
    $this->beginBody();
    if ($this->title !== null) {
        ?><title><?= Html::encode($this->title) ?></title><?php
    }
    echo $content;
    $this->endBody();
    $this->cssFiles = null;
    $this->endPage(true);
    Yii::$app->getResponse()->getHeaders()->setDefault('X-Pjax-Url', $request->getUrl());
    return;
}

AppAsset::register($this);

$user = User::current();
$route = Yii::$app->requestedRoute;

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no, user-scalable=no">
    <link rel="apple-touch-icon" sizes="180x180" href="/static/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/static/images/favicon-48x48.png" sizes="48x48">
    <link rel="icon" type="image/png" href="/static/images/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/static/images/favicon-24x24.png" sizes="24x24">
    <link rel="icon" type="image/png" href="/static/images/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/static/manifest.json">
    <link rel="yandex-tableau-widget" href="/static/manifest.json">
    <meta name="theme-color" content="#fff">
    <title><?= Html::encode($this->title) ?></title>
<?php $this->head(); ?>
</head>
<body>
<?php $this->beginBody(); ?>
    <div class="container">
        <header id="top-menu" class="sticky-top">
<?= $this->render('main/' . ($user ? 'header-user' : 'header-guest'), ['route' => $route]) ?>
        </header>
        <!-- beginPageContent -->
        <div id="pjax-container" class="page-container px-2 pb-2">
<?= $content ?>
        </div>
        <!-- endPageContent -->
    </div>
<div id="modal-page" class="modal-page-overlay"><div class="page" data-text-loading="<?= Yii::t('app', 'Загрузка...') ?>"></div></div>
<?php $this->endBody(); ?>

</body>
</html>
<?php $this->endPage();
