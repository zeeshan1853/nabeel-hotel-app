<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
//    public $basePath = '@webroot';
//    public $baseUrl = '@web';     
    public $sourcePath = '@api/web';
//    public $css = [
//        'css/site.css',
//    ];
//    public $js = [
//    ];
    public $depends = [
        'yii\web\YiiAsset',
    ];
}
