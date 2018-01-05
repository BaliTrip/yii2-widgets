<?php

use yii\helpers\Html;
use yii\helpers\Json;
use balitrip\uikit\ActiveForm;

$this->title = $model->isNewRecord ? 'Созадние виджета' : 'Настройка виджета';
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="applications items-create">

<?php $form = ActiveForm::begin(['id'=>'widget-form','layout'=>'stacked','field_width'=>'full','options'=>['data'=>['pjax'=>1]]]); ?>    

<div class="uk-grid uk-grid-small">
    
    <div class="uk-width-medium-4-5">

        <div class="uk-panel-box">

        	<?= $form->field($model, 'name')->textInput(); ?>

            <hr>

        	<?= $this->render($model->widgetModel->formView,[
        		'model'=>$model->widgetModel,
                'form'=>$form,
        	]); ?>

        </div>

        <hr>

        <div class="uk-panel-box">

        <div class="uk-form-row">
            <?= Html::activeLabel($model, 'bound', ['class' => 'uk-form-label']); ?>
            <div class="uk-form-controls">
            <?php $key = -1; if (count($model->bound)): ?>
                <?php foreach ($model->bound as $key => $value): ?>
                <div class="uk-grid uk-grid-small">
                    <div class="uk-width-medium-1-4">
                        <?= Html::activeTextInput($model, "bound[$key][module]", ['class' => 'uk-width-1-1','placeholder'=>'module']); ?>
                    </div>
                    <div class="uk-width-medium-1-4">
                        <?= Html::activeTextInput($model, "bound[$key][controller]", ['class' => 'uk-width-1-1','placeholder'=>'controller']); ?>
                    </div>
                    <div class="uk-width-medium-1-4">
                        <?= Html::activeTextInput($model, "bound[$key][action]", ['class' => 'uk-width-1-1','placeholder'=>'action']); ?>
                    </div>
                    <div class="uk-width-medium-1-4">
                        <?= Html::activeCheckbox($model, "bound[$key][except]", ['label' => 'except']); ?>
                    </div>
                </div>
                <?php endforeach ?>
            <?php else: ?>
                <div class="uk-grid uk-grid-small">
                    <div class="uk-width-medium-1-4">
                        <?= Html::activeTextInput($model, "bound[$key][module]", ['class' => 'uk-width-1-1','placeholder'=>'module']); ?>
                    </div>
                    <div class="uk-width-medium-1-4">
                        <?= Html::activeTextInput($model, "bound[$key][controller]", ['class' => 'uk-width-1-1','placeholder'=>'controller']); ?>
                    </div>
                    <div class="uk-width-medium-1-4">
                        <?= Html::activeTextInput($model, "bound[$key][action]", ['class' => 'uk-width-1-1','placeholder'=>'action']); ?>
                    </div>
                    <div class="uk-width-medium-1-4">
                        <?= Html::activeCheckbox($model, "bound[$key][except]", ['label' => 'except']); ?>
                    </div>
                </div>
            <?php endif ?>
                <a data-copy-row="<?=$key+1?>" style="margin-top:5px;margin-left:5px;display:inline-block">Добавить строку</a>
            </div>


        </div>

        </div>

	</div>

	<div class="uk-width-medium-1-5">

        <div class="uk-panel-box">

        	<?= $form->field($model, 'widget')->textInput(['disabled'=>'disabled']); ?>

            <?= $form->field($model, 'position')->textInput(['option' => 'value']); ?>

            <?= $form->field($model, 'state')->checkbox(); ?>

            <?= $form->field($model, 'cache')->checkbox(['option' => 'value']); ?>

            <?= $form->field($model, 'lang')->dropDownList(Yii::$app->widgets->languages, ['prompt' => 'Все языки']); ?>

            <?= $form->field($model, 'css_class')->textInput(); ?>

        </div>

        <div class="uk-margin-top">
		    <?=Html::submitButton('Сохранить',['name'=>'submitButton','value'=>1,'class'=>'uk-button uk-button-success uk-width-1-1'])?>
		</div>

    </div>

</div>

<?php ActiveForm::end(); ?>

</div>

<?php $script = <<<JAVASCRIPT

$('body').on("click","[data-copy-row]",function(){
    var key = Number($(this).data("copy-row"));
    var row = '<div class="uk-grid uk-grid-small"><div class="uk-width-medium-1-4"><input type="text" class="uk-width-1-1" name="Widgets[bound]['+key+'][module]" placeholder="module"></div><div class="uk-width-medium-1-4"><input type="text" class="uk-width-1-1" name="Widgets[bound]['+key+'][controller]" placeholder="controller"></div><div class="uk-width-medium-1-4"><input type="text" class="uk-width-1-1" name="Widgets[bound]['+key+'][action]" placeholder="action"></div><div class="uk-width-medium-1-4"><input type="hidden" name="Widgets[bound]['+key+'][except]" value="0"><label><input type="checkbox" name="Widgets[bound]['+key+'][except]" value="1"> except</label></div></div>';
    $(this).before(row);
    $(this).data("copy-row",key+1);
}).on("click","[data-up]",function(e){
    var prev = $(this).parents("div.row").prev();
    if (prev.length != 0) {
        prev.before($(this).parents("div.row"));
    }
    console.log('up',prev);
    e.preventDefault();
})
.on("click","[data-down]",function(e){
    var next = $(this).parents("div.row").next();
    if (next.length != 0) {
        next.after($(this).parents("div.row"));
    }
    console.log('down',next);
    e.preventDefault();
})
.on("click","[data-remove]",function(e){
    $(this).parents("div.row").remove();
})

JAVASCRIPT;

$this->registerJs($script,$this::POS_READY);