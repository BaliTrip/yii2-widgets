<?php

namespace balitrip\widgets\widgets\models;

use Yii;

class Review extends \yii\base\Model
{
    public $title;
    public $subtitle;
    public $content;
    public $image;

    public static function getName() {
        return 'Review';
    }

    public static function getDescription() {
        return 'Review widget';
    }

    public static function getFormView() {
        return '@balitrip/zoo/widgets/forms/review';
    }

    public function rules()
    {
        return [
            [['content'],'string'],
            [['title','image','subtitle'],'string','max'=>255],
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => 'Html & text',
            'image' => 'Url to photo',
            'title' => "Reviewer's name",
            'subtitle' => 'Short description',
        ];
    }

}
