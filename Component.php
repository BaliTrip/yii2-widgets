<?php

namespace balitrip\widgets;

use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use balitrip\widgets\models\Widgets;
use balitrip\widgets\models\WidgetsSearch;
use balitrip\widgets\helpers\ShortcodeHelper;

class Component extends \yii\base\Component { 

	public $languages = ['ru'=>'Русский','en'=>'English'];
	public $callbacks = [];

	public $customWidgetsPath;
	public $customWidgetsNamespace;
	public $attachWidget = [];

	private $widgets;
	private $widgetModels;
	private $bounds;

	public function findShortcodes($content) {

		$shortcode = new ShortcodeHelper;
		$shortcode->callbacks = $this->callbacks();

		return $shortcode->parse($content);
	}

	public function callbacks() {
		
		return array_merge([
			'uk-slideshow'=>['balitrip\uikit\widgets\Slideshow','widget'],
			'widget'=>['balitrip\zoo\widgets\Widget','widget'],
			//'anothershortcode'=>function($attrs, $content, $tag){},
		],$this->callbacks);
	}

	public function render($position) {

		$out = '';

		if (!empty($position)) {

			$key = 'balitrip_widgets_'.$position.'_'.Yii::$app->language;

			//Yii::$app->cache->flush();

    		$data = Yii::$app->cache->get($key);

    		if ($data === false) {

				$widgets = Widgets::find()
					->where(['state'=>1,'position'=>$position,'lang'=>['',null,Yii::$app->language]])
					->with('bounds')
					->orderBY('sort')
					->all();

				$data = \yii\helpers\ArrayHelper::toArray($widgets, [
				    'balitrip\widgets\models\Widgets' => [
				        'callback'=>'widget',
				        'bounds' => function ($model) {
				        	$bounds = ['only'=>[],'except'=>[]];
				        	if (count($model->bounds)) {
				        	foreach ($model->bounds as $bound) {	
				        		if ($bound['except']) {
				        			$bounds['except'][] = $bound['module'].'/'. $bound['controller'].'/'. $bound['action'];
				        		}			        
				        		else {
				        			$bounds['only'][] = $bound['module'].'/'. $bound['controller'].'/'. $bound['action'];
				        		}	
				        	}
				        	}
				            return $bounds;
				        },
				        'params' => function ($model) {
				        	$params = $model->getParams();
				        	$params['options'] = ['class'=>$model->css_class];
							$params['cache'] = $model->cache;
							$params['name'] = $model->name;
							$params['id'] = $model->id;
				            return $params;
				        },
				    ],
				]);

				$dependency = new \yii\caching\ChainedDependency([
					'dependencies'=> [
						new \yii\caching\DbDependency(['sql' => 'SELECT MAX(updated_at) FROM {{%widgets}}'])
					],
				]);
    			
    			Yii::$app->cache->set($key, $data, 0, $dependency);

    		}

    		$this->getWidgets();

			foreach ($data as $widget) {

				$out .= $this->callWidget($widget);

			}


		}

		return $out;

	}

	public function callWidget($widget) {

	    if ($this->widgets === null)
            $this->getWidgets();

		if (!empty($this->widgets[$widget['callback']])) {
			if (!isset($widget['bounds']) || $this->checkBounds($widget['bounds'])) {
				return call_user_func([$this->widgets[$widget['callback']],'widget'], $widget['params']);
			}
		}

	}

	public function getWidgetsModels() {

		if ($this->widgetModels === null) {

	        $widgets = [];

	        $paths = ['balitrip\widgets\widgets\models'=>'@balitrip/widgets/widgets/models'];

	        if (Yii::$app->has('zoo')) {
	        	$paths['balitrip\zoo\widgets\models'] = '@balitrip/zoo/widgets/models';
	        }

	        if ($this->customWidgetsPath !== null && $this->customWidgetsNamespace !== null) {
	        	$paths[$this->customWidgetsNamespace.'\\models'] = rtrim($this->customWidgetsPath,'/').'/models';
	        }

	        foreach ($paths as $namespace => $path) {

	        	$path = rtrim(Yii::getAlias($path),DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

	        	$widgetModels = \yii\helpers\FileHelper::findFiles($path);

		        foreach ($widgetModels as $key => $model) { 
			        $model = str_replace([$path,'.php'], '', $model);
			        $widgets[$model] = rtrim($namespace,"\\")."\\".$model;
			    }

	        }

	        $this->widgetModels = $widgets;

        }

        return $this->widgetModels;
    }

    public function getWidgets() {

		if ($this->widgets === null) {

	        $widgets = [];

	        $paths = ['balitrip\widgets\widgets'=>'@balitrip/widgets/widgets'];

	        if (Yii::$app->has('zoo')) {
	        	$paths['balitrip\zoo\widgets'] = '@balitrip/zoo/widgets';
	        }

	        if ($this->customWidgetsPath !== null && $this->customWidgetsNamespace !== null) {
	        	$paths[$this->customWidgetsNamespace] = $this->customWidgetsPath;
	        }



	        foreach ($paths as $namespace => $path) {

	        	$path = rtrim(Yii::getAlias($path),DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

	        	$widgetModels = \yii\helpers\FileHelper::findFiles($path);

		        foreach ($widgetModels as $key => $model) {
			        $model = str_replace([$path,".php"], '', $model);
			        $widgets[$model] = rtrim($namespace,"\\")."\\".$model;
			    }

	        }

	        $this->widgets = $widgets;

        }

        return $this->widgets;
    }

    public function checkBounds($bounds) {

    	$m = Yii::$app->controller->module->id;
    	$c = Yii::$app->controller->id;
    	$a = Yii::$app->controller->action->id;

    	$except = $bounds['except'];
    	$only = $bounds['only'];

    	if (in_array('//'.$a, $except) || in_array('/'.$c.'/'.$a, $except) || in_array($m.'/'.$c.'/'.$a, $except)) {
    		return false;
    	}

    	if (count($only)) {
    		
    		if (in_array('//'.$a, $only) || in_array('/'.$c.'/'.$a, $only) || in_array($m.'/'.$c.'/'.$a, $only)) {
    			return true;
    		}

    		return false;

    	}
    		
    	return true;

    }

}