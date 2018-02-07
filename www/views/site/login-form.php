<?php

use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $popup bool */

$popup = !empty($popup);
?>

<div class="card card-teal<?= $popup ? ' modal' : '' ?> loginForm" tabindex="-1" role="dialog">
    <div class="card-header"><?= Yii::t('app', 'Вход на сайт') ?></div>
    <div class="card-body">
        <form action="<?= Url::toRoute(['/site/login', 'modal' => $popup ? true : null]) ?>">
            <div class="form-group row">
              <label for="loginInputLogin<?= $popup ?>Form" class="col-4 col-sm-3 col-form-label text-right"><?= Yii::t('app', 'Логин') ?></label>
              <div class="col-8 col-sm-9">
                <input type="text" class="form-control login fc-required fc-change fc-keypress" id="loginInputLogin<?= $popup ?>Form" data-fc-change="loginFormChange" value="">
              </div>
            </div>
            <div class="form-group row">
              <label for="passwordInputLogin<?= $popup ?>Form" class="col-4 col-sm-3 col-form-label text-right"><?= Yii::t('app', 'Пароль') ?></label>
              <div class="col-8 col-sm-9">
                <input type="text" class="form-control password fc-required fc-change fc-keypress" id="passwordInputLogin<?= $popup ?>Form" data-fc-change="loginFormChange" value="">
              </div>
            </div>
            <div class="form-text text-muted text-center">
              <button type="button" class="btn btn-link p-2 m-0" data-fc-click="popover" data-max-width="380px" title="<?= Yii::t('app', 'Информация') ?>" data-content="<?= Yii::t('app', 'Начните пользоваться, логин и пароль сайт создаст автоматически.') ?>" data-btn-close="<?= Yii::t('app', 'ЗАКРЫТЬ') ?>"><?= Yii::t('app', 'Как зарегистрироваться?') ?></button>
              <button type="button" class="btn btn-link p-2 m-0" data-fc-click="popover" data-max-width="410px" title="<?= Yii::t('app', 'Информация') ?>" data-content="<?= Yii::t('app', 'К сожалению восстановить пароль невозможно. Мы его не храним.') ?>" data-btn-close="<?= Yii::t('app', 'ЗАКРЫТЬ') ?>"><?= Yii::t('app', 'Забыли пароль?') ?></button>
            </div>
            <div class="d-flex justify-content-end align-items-center">
                <div class="text-danger mr-3 bad-password collapse" role="alert" data-msg-empty="<?= Yii::t('app', 'Пустые логин или пароль') ?>" data-msg-bad="<?= Yii::t('app', 'Неверный логин или пароль') ?>" data-msg-error="<?= Yii::t('app', 'Ошибка на сервере') ?>"></div>
                <div class="text-info mr-3 loading collapse" role="alert"><?= Yii::t('app', 'Отправка данных...') ?></div>
                <button type="button" class="btn btn-teal" data-fc-click="loginForm"><?= Yii::t('app', 'Войти') ?></button>
            </div>
        </form>
    </div>
</div>
