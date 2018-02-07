<?php

use yii\helpers\Html;
use app\models\User;

/* @var $this yii\web\View */

$user = User::current();

$this->title = 'Выход';
?>
<h1><?= $this->title ?></h1>
<?= Html::beginForm() ?>

  <div>Перед тем как выйти, запишите свой логин и пароль.</div>
  <div class="card my-2 p-2">
    <div><span class="text-success">Ваш логин:</span> <?= Html::encode($user->getLogin()) ?></div>
    <div><span class="text-success">Ваш пароль:</span> <?= Html::encode($user->getPwd()) ?></div>
  </div>
  <button type="submit" class="btn btn-primary">Выйти и очистить данные браузера</button>
<?= Html::endForm() ?>

