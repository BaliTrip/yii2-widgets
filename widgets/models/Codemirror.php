<?php

namespace balitrip\widgets\widgets\models;

use Yii;

class Codemirror extends \yii\base\Model
{
    public $content;

    public static function getName() {
        return 'CodeMirror';
    }

    public static function getDescription() {
        return 'Text widget with html && js codemirror';
    }

    public static function getFormView() {
        return '@balitrip/widgets/widgets/forms/codemirror';
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
            'content' => 'Type your code here:',
        ];
    }

}
