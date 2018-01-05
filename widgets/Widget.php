<?php

namespace balitrip\widgets\widgets;

use Yii;

class Widget extends \yii\base\Widget
{
    public $id;
    public $options = [];
    public $name;
    public $cache = false;
    public $container = true;

	public static function widget($config = [])
    {
    	$id = 'widget-'.(!empty($config['id']) ? $config['id'] : uniqid());
    	$cache_id = !empty($config['cache']) && $config['cache'] ? $id : false;
		
        $defaultConfig = [
            'options'=>[
                'id'=>$id,
                'class'=>'widget-'.strtolower((new \ReflectionClass(self::classname()))->getShortName()),
            ],
        ];

        $config = array_merge_recursive($defaultConfig, $config);

    	if (false && $cache_id) {
    		$data = Yii::$app->cache->get($cache_id); 
    		if ($data === false) {
	            $data = parent::widget($config);
	            Yii::$app->cache->set($cache_id, $data);
	        }
	        return $data;
    	} 

        return \yii\helpers\Html::tag('div',parent::widget($config),$config['options']);
    }

}