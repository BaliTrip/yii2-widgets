<?php

namespace balitrip\widgets\assets;

use yii\web\AssetBundle;

class AdminAsset extends AssetBundle
{
    public $sourcePath = '@balitrip/widgets/assets';

    public $css = [
        'css'=>'css/admin.css',
    ];

    public $js = [
        
    ];

    public $depends = [
        'balitrip\uikit\UikitAsset',
    ];

    public $publishOptions = [
        'forceCopy'=> YII_ENV_DEV ? true : false,
    ];
}