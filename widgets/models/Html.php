<?php

namespace balitrip\widgets\widgets\models;

use Yii;

class Html extends \yii\base\Model 
{
    public $content;

    public static function getName() {
        return 'Html';
    }

    public static function getDescription() {
        return 'Content widget with html editor';
    }

    public static function getFormView() {
        return '@balitrip/widgets/widgets/forms/html';
    }

    public function rules()
    {
        return [
            [['content'],'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => 'Html & text',
        ];
    }

}
