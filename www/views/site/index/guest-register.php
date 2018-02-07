<?php

use yii\helpers\Url;

/* @var $this yii\web\View */
?>
<div id="guest-register-form">
    <div class="alert alert-warning px-2 py-1 m-0 text-center guest-block small-border-x-0 small-mx--2" role="alert">
        <div data-block="1">
            <div class="font-italic small">Для правильного отображения результатов поиска ответьте на несколько вопросов.</div>
            <div data-step="1">
                <button type="button" class="btn btn-grey2 m-05" data-fc-click="guestForm" data-param="female" data-next-step="2">Я девушка<span class="d-none d-sm-inline">, женщина</span></button><button type="button" class="btn btn-grey2 m-05 fcs-btn" data-fc-click="guestForm" data-param="male" data-next-step="2">Я парень<span class="d-none d-sm-inline">, мужчина</span></button>
            </div>
            <div data-step="2" class="collapse">
                Ищу <button type="button" class="btn btn-grey2 m-05" data-fc-click="guestForm" data-param="findMale" data-next-step="3">Парня</button><button type="button" class="btn btn-grey2 m-05" data-fc-click="guestForm" data-param="findFemale" data-next-step="3">Девушку</button><button type="button" class="btn btn-grey2 m-05" data-fc-click="guestForm" data-next-step="3">Друзей</button>
            </div>
            <div data-step="3" class="collapse">
                Я тут для 
                <button type="button" class="btn btn-grey2 m-05" data-fc-click="guestForm" data-param="virt" data-next-block="2" data-next-step="5">Общения</button><button type="button" class="btn btn-grey2 m-05" data-fc-click="guestForm" data-param="real" data-next-block="2" data-next-step="5">Встреч</span></button><button type="button" class="btn btn-grey2 m-05" data-fc-click="guestForm" data-param="virt,real" data-next-block="2" data-next-step="5">Общения и встреч</button>
            </div>
        </div>
        <div data-block="2" class="collapse">
            <strong>Важно!</strong> Для использования сайта вы должны подвердить что:
            <div data-step="5" class="collapse">
                <button type="button" class="btn btn-grey2 m-05" data-fc-click="guestForm" data-action="<?= Url::toRoute(['/site/register']) ?>"><b>Мне есть 16 лет</b> <br class="d-md-none">и я согласен/согласна <br class="d-sm-none">соблюдать правила сайта</button>
            </div>
        </div>
    </div>
</div>