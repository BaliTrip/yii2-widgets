<?php

namespace balitrip\widgets\widgets\models;

use Yii;

class UkSlideshowPath extends \yii\base\Model
{
    public $path; 

    public static function getName() {
        return 'Uk-Slideshow Path';
    }

    public static function getDescription() {
        return 'Uikit Slideshow widget';
    }

    public static function getFormView() {
        return '@balitrip/widgets/widgets/forms/uk-slideshow-path';
    }

    public function rules()
    {
        return [
            [['path'],'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'path' => 'Path to images folder',
        ];
    }

}