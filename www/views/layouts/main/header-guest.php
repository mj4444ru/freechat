<?php

use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $route string */

$route = isset($route) ? $route : '';
?>
            <nav class="navbar nav-grey">
                <a class="nav-link<?= $route == 'site/index' ? ' active' : '' ?> nav-brand" href="<?= Url::toRoute(['/site/index']) ?>" tabindex="-1"><i class="material-icons">&#xE87D;</i></a>
                <a class="nav-link<?= $route == 'site/index' ? ' active' : '' ?>" href="<?= Url::toRoute(['/site/index'])?>" tabindex="-1"><?= Yii::t('app', 'Поиск') ?></a>
                <a class="nav-link<?= $route == 'site/info' ? ' active' : '' ?>" href="<?= Url::toRoute(['/site/info']) ?>" tabindex="-1"><?= Yii::t('app', 'Информация') ?></a>
                <a class="nav-link<?= $route == 'site/rules' ? ' active' : '' ?> d-none d-sm-block" href="<?= Url::toRoute(['/site/rules']) ?>" tabindex="-1"><?= Yii::t('app', 'Правила') ?></a>
                <a class="nav-link<?= $route == 'site/login' ? ' active' : '' ?> ml-auto" href="<?= Url::toRoute(['/site/login']) ?>" data-fancybox="login" data-type="ajax" tabindex="-1"><?= Yii::t('app', 'Вход') ?></a>
            </nav>
