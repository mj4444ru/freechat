<?php

use app\models\User;
use app\models\Online;

/* @var $this yii\web\View */

$user = User::current();
$this->title = 'Знакомства для свободного общения, бесплатно без смс';
?>
<h4 class="text-primary mt-2">Добро пожаловать на сайт знакомств и свободного общения.</h4>
<?php
if (!$user) {
    echo $this->render('index/guest-register');
}
?>
<div id="search-container">
  <div id="search-form" class="search-form px-4 small-mx--2 small-border-x-0 user-block<?= $user ? '' : ' collapse' ?>">
    <ul class="nav nav-grey justify-content-center flex-wrap">
      <li class="nav-item">
        <span class="navbar-text px-1">Хочу найти</apan>
      </li>
      <li class="nav-item dropdown">
        <button type="button" class="btn btn-link fc-btn nav-link px-1 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-event="findForm" data-params="findMale" tapindex="-1">друзей</button>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="#"><div class="f-icon">Девушку</div></a>
          <a class="dropdown-item" href="#"><div class="m-icon">Парня</div></a>
          <a class="dropdown-item" href="#"><div class="mf-icon">Друзей</div></a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <button type="button" class="btn btn-link fc-btn nav-link px-1 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-event="findForm" data-params="findMale" tapindex="-1">для общения</button>
        <div class="dropdown-menu">
          <a class="dropdown-item" href="#">Для общения</a>
          <a class="dropdown-item" href="#">Для встречи</a>
        </div>
      </li>
    </ul>
  </div>
  <div id="search-result" class="search-result">
<?php
$currentUserId = $user ? $user->id : null;
$result = Online::find()->onlineVirt()->orderBy(['up_at' => SORT_DESC])->all();
foreach ($result as $model) {
    $userId = $model->user_id;
    $gender = $model->gender;
    $iconList = ['def' => 'empty-icon', Online::GENDER_M => 'm-icon', Online::GENDER_F => 'f-icon', Online::GENDER_MF => 'm-icon', Online::GENDER_FM => 'f-icon', Online::GENDER_MM => 'mm-icon', Online::GENDER_FF => 'ff-icon'];
    $icon = $gender && isset($iconList[$gender]) ? $iconList[$gender] : $iconList['def'];
    $name = $model->getNameFromCache();
    $age = $model->age;
    $age = $age ? ' <span class="age">' . $age . '</span>' : '';
    $town = $model->getCityFromCache();
    $town = $town ? ' <span class="town">' . $town[0] .'</span>' : '';
    $search = $model->getFindWho();
    $virt = $model->isVirt() ? ' <span class="virt" title="Общаюсь">в<span class="d-none d-sm-inline">ирт</span></span>' : '';
    $real = $model->isReal() ? ' <span class="real" title="Встречаюсь">р<span class="d-none d-sm-inline">еал</span></span>' : '';
    if ($currentUserId == $userId) {
        $online = ' <span class="online" title="Это Вы">вы</span></span>';
    } else {
        $online = $model->isOnline() ? ' <span class="online" title="Сейчас на сайте">о<span class="d-none d-sm-inline">нлайн</span></span>' : '';
    }

    echo sprintf('<div class="user-line"><a class="%s" href="/%s"><div><span class="name">%s</span>%s%s <span class="find-who">ищет %s</span>%s%s%s</div></a></div>', $icon, $userId, $name, $age, $town, $search, $virt, $real, $online);
}
?>
  </div>
</div>
