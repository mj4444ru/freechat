<?php

use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'Меню пользователя';
?>
<h1><?= $this->title ?></h1>
<div class="list-group">
  <a href="<?= Url::toRoute(['/site/profile']) ?>" class="list-group-item list-group-item-action">Анкета</a>
  <a href="<?= Url::toRoute(['/site/settings']) ?>" class="list-group-item list-group-item-action">Настройки</a>
  <a href="<?= Url::toRoute(['/site/help']) ?>" class="list-group-item list-group-item-action">Помощь</a>
  <a href="<?= Url::toRoute(['/site/info']) ?>" class="list-group-item list-group-item-action">Информация</a>
  <a href="<?= Url::toRoute(['/site/rules']) ?>" class="list-group-item list-group-item-action">Правила</a>
  <a href="<?= Url::toRoute(['/site/logout']) ?>" class="list-group-item list-group-item-action">Выход</a>
</div>
