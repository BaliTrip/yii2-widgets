<?php

namespace worstinme\widgets\widgets\models;

use Yii;

class YandexMap extends \yii\base\Model
{
    public $point = '55.8, 37.8';
    public $center = '55.76, 37.64';
    public $zoom = 10;
    public $height = '300px';
    public $width = '100%';
    public $ballon;
    public $preset = 'islands#redCircleDotIcon';
    public $iconColor = '#3b5998';

    public static function getName() {
        return 'YandexMap';
    }

    public static function getDescription() {
        return '';
    }

    public static function getFormView() {
        return '@worstinme/widgets/widgets/forms/yandex-map';
    }

    public function rules()
    {
        return [
            [['ballon'],'string'],
            [['zoom'],'integer'],
            [['center','point','width','height','iconColor','preset'],'string','max'=>255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'coordinates' => 'Coordinates',
        ];
    }

}