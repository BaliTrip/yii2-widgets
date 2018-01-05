<?php

namespace balitrip\widgets\widgets;

use Yii;

class Html extends Widget
{
    public $content;

    public function run()
    {
        return $this->content;
    }   

}