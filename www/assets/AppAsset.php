<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        ['https://fonts.googleapis.com/icon?family=Material+Icons', 'crossorigin' => 'anonymous'],
        ['https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css', 'crossorigin' => 'anonymous'],
        ['https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.css', 'crossorigin' => 'anonymous'],
        'static/css/main.scss',
    ];
    public $js = [
        ['https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js', 'crossorigin' => 'anonymous'],
        ['https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js', 'crossorigin' => 'anonymous'],
        ['https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js', 'crossorigin' => 'anonymous'],
        ['https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.2.5/jquery.fancybox.min.js', 'crossorigin' => 'anonymous'],
//!!!mj4444        ['https://unpkg.com/vue', 'crossorigin' => 'anonymous'],
        'static/js/jquery.pjax.js',
        'static/js/main.js',
    ];
    public $depends = [
    ];
}
