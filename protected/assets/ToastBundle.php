<?php


namespace prime\assets;


use yii\web\AssetBundle;

class ToastBundle extends AssetBundle
{

    public $sourcePath = '@npm/izitoast/dist';
    public $js = [
        'js/iziToast.js'
    ];
    public $css = [
        'css/iziToast.css'
    ];

}