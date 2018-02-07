<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\View;
use app\models\User;
use app\models\Profile;

/* @var $this yii\web\View */
/* @var $model Profile */

$user = User::current();
$userId = $model->user_id;
$isMale = !$model->isFemale();
$icon = $isMale ? 'male-icon' : 'female-icon';
// getNameFromCache - возвращает безопасный html
$name = $model->getNameFromCache();
$age = $model->age;
$ageText = $age ? Profile::getWordFormRu($age, 'год', 'года', 'лет') : '';
// getCityFromCache - возвращает безопасный html
$town = $model->getCityFromCache();
$search = $model->getFindWho();

$title = $model->name_id ? Html::decode($model->getNameFromCache()) . ', ' : '';
$title .= $model->getGenderText();
$title .= $age ? sprintf(' %s %s', $age, $ageText) : '' ;
$title .= $town ? ' ' . sprintf('из города %s', Html::decode($town[0])) : '' ;
$title .= (' ищет ') . $search;

$virt = $model->isVirt() ? ' <span class="virt" title="Общаюсь">вирт</span>' : '';
$real = $model->isReal() ? ' <span class="real" title="Встречаюсь">реал</span>' : '';
if ($user && $user->id == $userId) {
    $online = ' <span class="online" title="Это Вы">это вы</span>';
} else {
    $online = $model->isOnline() ? ' <span class="online" title="Сейчас на сайте">онлайн</span>' : '';
}
$age = $age ? ' ' . sprintf('<span class="age">%s</span> %s', $age, $ageText) : '';
$town = $town ? sprintf('<span class="town">%s</span>', $town[1]) . ' ' : '';
$search = $model->getFindWho();
$ageFrom = $model->age_from;
$ageTo = $model->age_to;
if ($ageFrom && $ageTo) {
    $searchAge = ' ' . sprintf('в возрасте %s-%s %s', $ageFrom, $ageTo, Profile::getWordFormRu($ageTo, 'года', 'лет', 'лет'));
} elseif ($ageFrom)  {
    $searchAge = ' ' . sprintf('в возрасте от %s %s', $ageFrom, Profile::getWordFormRu($ageFrom, 'года', 'лет', 'лет'));
} elseif ($ageTo)  {
    $searchAge = ' ' . sprintf('в возрасте до %s %s', $ageTo, Profile::getWordFormRu($ageTo, 'года', 'лет', 'лет'));
} else  {
    $searchAge = '';
}
$messages = $user ? $user->profile->getLastMessages($userId) : null;

$this->title = $title;
?>
<h1 class="anketa"><span class="<?= $icon ?>"><?= $name ?></span><span class="info user-line"><?= $age . $online ?></span></h1>
<div class="user-line"><?= $town ?></div>
<div class="user-line"><span class="find-who">Ищет <?= $search . $searchAge ?></span><?= $virt . $real ?></div>
<div class="mt-2 small text-muted">Написать личное сообщение</div>
<div class="message-form">
    <div contenteditable="true" placeholder="Напишите сообщение..." class="message-editor form-control"></div>
    <div class="d-flex flex-wrap align-items-center mt-2 dropup">
        <button<?= $user ? '' : ' data-toggle="dropdown"' ?> class="message-send btn btn-primary btn-sm fc-btn" data-event="sendMessage" data-action="<?= Url::toRoute(['send-message']) ?>"><i class="material-icons visible-disabled rotate">&#xE863;</i><i class="material-icons hidden-disabled">&#xE158;</i> Отправить</button>
<?php if (!$user) : ?>
        <div class="dropdown-menu" data-action="<?= Url::toRoute(['/site/guest-form']) ?>">
<?php if ($isMale) : ?>
            <button class="dropdown-item fc-btn" type="button" data-event="sendMessageGenderForm" data-params="registerMale"><div class="m-icon">Я парень и <b>мне есть 16 лет</b></div></button>
            <button class="dropdown-item fc-btn" type="button" data-event="sendMessageGenderForm" data-params="registerFemale"><div class="f-icon">Я девушка и <b>мне есть 16 лет</b></div></button>
<?php else : ?>
            <button class="dropdown-item fc-btn" type="button" data-event="sendMessageGenderForm" data-params="registerFemale"><div class="f-icon">Я девушка и <b>мне есть 16 лет</b></div></button>
            <button class="dropdown-item fc-btn" type="button" data-event="sendMessageGenderForm" data-params="registerMale"><div class="m-icon">Я парень и <b>мне есть 16 лет</b></div></button>
<?php endif; ?>
        </div>
<?php endif; ?>
        <div class="text-danger ml-3 message-send-error collapse" role="alert" data-msg-deny="Отправка запрещена" data-msg-error="Ошибка на сервере или сервер недоступен"></div>
        <div class="text-info ml-3 message-send-loading collapse" role="alert">Отправка данных...</div>
    </div>
</div>
<div class="mt-2 small text-muted">История сообщений</div>
<div id="messages-history" class="mb-2 messages-history" data-from="<?= $user ? $user->id : 0 ?>" data-to="<?= $userId ?>" data-last-msg-id="0">
    <div class="card"><div class="card-block">Здесь будет ваша переписка.</div></div>
</div>
<?php
if ($messages) {
    $this->registerJs('var userMessages = ' . Json::encode($messages), View::POS_END);
}