<?php

namespace balitrip\widgets\widgets;

use Yii;
use yii\helpers\FileHelper;

class UkSlideshow extends Widget
{

    public $content = [];
    public $images = [];
    public $type = 1;
    public $height;
    public $slide_container = false;
    public $autoplay;

    /*  item => [
            'img'=>'/img.jpg', from @webroot
            'overlay'=>'',
            'item'=>'',
        ]   */

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        $options = [];

        if (!empty($this->height)) {
            $options['height'] = $this->height;
        }

        if (!empty($this->autoplay)) {
            $options['autoplay'] = $this->autoplay;
        }
        
        if ($this->type == 1 && count($this->content)) {

            return $this->render('uk-slideshow',[
                'items'=>$this->content,
                'images'=>$this->images,
                'options'=>$options,
            ]); 

        }

        return null;
    }   

}