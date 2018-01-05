<?php

namespace balitrip\widgets\widgets\models;

use Yii;

class UkSlideshow extends \yii\base\Model
{
    public $content = [];
    public $images = [];
    public $type = 1;
    public $height;
    public $slide_container = false;
    public $autoplay;

    public static function getName() {
        return 'Uk-Slideshow';
    }

    public static function getDescription() {
        return 'Uikit Slideshow widget';
    }

    public static function getFormView() {
        return '@balitrip/widgets/widgets/forms/uk-slideshow';
    }

    public function getSlides() {
        return $this->content;
    }

    public function setSlides($a) {
        foreach ((array)$a as $key => $value) {
            $value = trim(strip_tags($value));
            if (empty($value)) {
                unset($a[$key]);
                if (isset($this->image[$key])) {
                    unset($this->image[$key]);
                }
            }
        }

        return $this->content = $a;
    }

    public function getImage() {
        return $this->images;
    }

    public function setImage($a) {
        if (is_array($a)) {
            foreach ((array)$a as $key => $value) {
                $value = trim(strip_tags($value));
                if (empty($value)) {
                    unset($a[$key]);
                }
            }
        }
        return $this->images = $a;
    }

    public function rules()
    {
        return [
            ['slides','each','rule'=>['string']],
            ['content','each','rule'=>['string']],
            ['image','each','rule'=>['string']],
            ['images','each','rule'=>['string']],
            [['type','autoplay','slide_container'],'integer'],
            ['height','string','max'=>255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'slides' => 'Html & text',
            'image' => 'Image url',
        ];
    }


}