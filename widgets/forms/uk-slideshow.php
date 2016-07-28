<?php

use yii\helpers\Html;
use worstinme\uikit\ActiveForm;
use mihaildev\elfinder\InputFile;
use yii\widgets\Pjax;

$types = [
	1=>'Кастомные слайды',
	2=>'Слайдер изображений из папки',
	3=>'Картинки с оверлеями',
]; 

$_type = Yii::$app->request->post('type');
$_reload = Yii::$app->request->post('reload');

?>

<?php Pjax::begin(['id'=>'pjax','timeout'=>5000,'options'=>['data-uk-observe'=>true]]); ?> 

<div class="uk-form-row">
	<div class="uk-button-group">
	<?php foreach ($types as $key => $type): ?>
		<?php if (($_type === null && $model->type == $key) || $_type == $key): $model->type = $key; ?>
			<?= Html::a($type,'#',['class'=>'uk-button uk-button-primary uk-active']); ?>
			<?= Html::activeHiddenInput($model, 'type'); ?>
		<?php else: ?>	
			<?= Html::a($type,null,['class'=>'uk-button','data'=>[
				'method'=>'post','params'=>['type'=>$key,'reload'=>true],'pjax'=>true
			]]); ?>
		<?php endif ?>
	<?php endforeach ?>
	</div>
</div>

<?php if ($model->type == 1): ?>
<div>
	<?php $key = -1; foreach ($model->slides as $key => $value): ?>
	<div class="uk-panel uk-margin-top row">
		<div class="uk-float-right">
			<?=$this->render('@worstinme/widgets/views/default/_row-buttons',['key'=>$key])?>
		</div>
		<?=$form->field($model, "slides[$key]")->widget(\worstinme\jodit\Editor::className(), [
		    'settings' => [
		        'buttons'=>[
		        	'source', '|', 'bold', 'italic', '|', 'ul', 'ol', '|', 'font', 'fontsize', 'brush', 'paragraph', '|','image', 'table', 'link', '|', 'left', 'center', 'right', 'justify', '|', 'undo', 'redo', '|', 'hr', 'eraser', 'fullsize'
		        ],
		    ],
		]);?>
		<?=$form->field($model, "images[$key]")->widget(InputFile::className(), [
		    'language'      => 'ru',
		    'controller'    => 'elfinder', // вставляем название контроллера, по умолчанию равен elfinder
		    'filter'        => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
		    'options'=>['class'=>'uk-form-width-large'],
		    'buttonOptions'=>['class'=>'uk-button uk-button-primary'],
		]);?>
	</div>
	<?php endforeach; $key++; ?>
	<?php if (!count($model->slides) || ($_reload !== null && $_type === null)) : ?>
	<div class="uk-panel uk-margin-top">
		<div class="uk-float-right">
			<?=$this->render('@worstinme/widgets/views/default/_row-buttons',['key'=>$key,'last'=>true])?>
		</div>
		<?=$form->field($model, "slides[$key]")->widget(\worstinme\jodit\Editor::className(), [
		    'settings' => [
		        'buttons'=>[
		        	'source', '|', 'bold', 'italic', '|', 'ul', 'ol', '|', 'font', 'fontsize', 'brush', 'paragraph', '|','image', 'table', 'link', '|', 'left', 'center', 'right', 'justify', '|', 'undo', 'redo', '|', 'hr', 'eraser', 'fullsize'
		        ],
		    ],
		]);?>
		<?=$form->field($model,  "images[$key]")->widget(InputFile::className(), [
		    'language'      => 'ru',
		    'controller'    => 'elfinder', // вставляем название контроллера, по умолчанию равен elfinder
		    'filter'        => 'image',    // фильтр файлов, можно задать массив фильтров https://github.com/Studio-42/elFinder/wiki/Client-configuration-options#wiki-onlyMimes
		    'options'=>['class'=>'uk-form-width-large'],
		    'buttonOptions'=>['class'=>'uk-button uk-button-primary'],
		]);?>
	</div>
	<?php endif ?>
</div>

<?php elseif ($model->type == 2): ?>

	<div class="uk-form-row">
	<?= Html::activeLabel($model, 'image',['class'=>'uk-form-label']); ?> 

		<?php foreach ($model->image as $key=>$image): ?>
		<div class="uk-from-controls">
			<?= InputFile::widget([
			    'language'   => 'ru',
			    'controller' => 'elfinder', 
			    'model'       => $model,
			    'attribute'      => "image[$key]",
			    'buttonName'=>'Выбрать',
			    'options'=>['class'=>'uk-form-width-large'],
			    'buttonOptions'=>['class'=>'uk-button uk-button-primary'],
			]); ?>
		</div>	
		<?php endforeach ?>

		<div class="uk-from-controls">
			<?= InputFile::widget([
				    'language'   => 'ru',
				    'controller' => 'elfinder', 
				    'model'       => $model,
				    'attribute'      => "image[]",
				    'buttonName'=>'Выбрать',
			    'options'=>['class'=>'uk-form-width-large'],
				    'buttonOptions'=>['class'=>'uk-button uk-button-primary'],
				]); ?>
		</div>

	</div>

<?php elseif ($model->type == 3): ?>

<?php endif ?>

<?= Html::a('Добавить строку', $url = null, ['data' => ['method'=>'post','params'=>['reload'=>true],'pjax'=>true]]); ?>

<?php Pjax::end(); ?>

<?php $script = <<<JAVASCRIPT

$.pjax.defaults.scrollTo = false;

JAVASCRIPT;

$this->registerJs($script,$this::POS_READY);