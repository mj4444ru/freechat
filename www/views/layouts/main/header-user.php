<?php

use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $route string */

$route = isset($route) ? $route : '';
?>
            <nav class="navbar nav-grey navbar-user">
                <a class="nav-link<?= $route == 'site/index' ? ' active' : '' ?> nav-brand" href="<?= Url::toRoute(['/site/index']) ?>" tabindex="-1"><i class="material-icons">&#xE87D;</i></a>
                <a class="nav-link<?= $route == 'site/index' ? ' active' : '' ?>" href="<?= Url::toRoute(['/site/index'])?>" tabindex="-1"><i class="material-icons">&#xE8B6;</i><span class="d-none d-md-inline"> <?= Yii::t('app', 'Поиск') ?></span></a>
                <a class="nav-link<?= $route == 'site/dailogs' ? ' active' : '' ?>" href="<?= Url::toRoute(['/site/dailogs']) ?>" tabindex="-1"><i class="material-icons">&#xE0E1;</i><span class="d-none d-md-inline"> <?= Yii::t('app', 'Диалоги') ?></span></a>
                <a class="nav-link<?= $route == 'site/requests' ? ' active' : '' ?>" href="<?= Url::toRoute(['/site/requests']) ?>" tabindex="-1"><i class="material-icons">&#xE87E;</i><span class="d-none d-md-inline"> <?= Yii::t('app', 'Предложения') ?></span></a>
                <div class="nav-item dropdown ml-auto">
                <a class="nav-link<?= $route == 'site/user-menu' ? ' active' : '' ?> dropdown-toggle" href="<?= Url::toRoute(['/site/user-menu']) ?>" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false" tabindex="-1"><span class="d-none d-sm-inline js-user-name"></span> <i class="material-icons">&#xE851;</i></a>
                <div class="dropdown-menu dropdown-menu-right">
                    <a class="dropdown-item<?= $route == 'site/profile' ? ' active' : '' ?> fc-btn" data-event="flatPage" href="<?= Url::toRoute(['/site/profile']) ?>" tabindex="-1"><?= Yii::t('app', 'Анкета') ?></a>
                    <a class="dropdown-item<?= $route == 'site/settings' ? ' active' : '' ?>" href="<?= Url::toRoute(['/site/settings']) ?>" tabindex="-1"><?= Yii::t('app', 'Настройки') ?></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item<?= $route == 'site/help' ? ' active' : '' ?>" href="<?= Url::toRoute(['/site/help']) ?>" tabindex="-1"><?= Yii::t('app', 'Помощь') ?></a>
                    <a class="dropdown-item<?= $route == 'site/info' ? ' active' : '' ?>" href="<?= Url::toRoute(['/site/info']) ?>" tabindex="-1"><?= Yii::t('app', 'Информация') ?></a>
                    <a class="dropdown-item<?= $route == 'site/rules' ? ' active' : '' ?>" href="<?= Url::toRoute(['/site/rules']) ?>" tabindex="-1"><?= Yii::t('app', 'Правила') ?></a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item<?= $route == 'site/logout' ? ' active' : '' ?>" href="<?= Url::toRoute(['/site/logout']) ?>" tabindex="-1"><?= Yii::t('app', 'Выход') ?></a>
                </div>
                </div>
            </nav>
