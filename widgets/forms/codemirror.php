<?php

use yii\helpers\Html;
use balitrip\uikit\ActiveForm;
use conquer\codemirror\CodemirrorWidget;
use conquer\codemirror\CodemirrorAsset;


$form = ActiveForm::begin(['layout' => 'horizontal', 'field_width' => 'full']); ?>

<?= $form->field($model, 'content')->widget(
    CodemirrorWidget::className(),
    [
        'assets' => [
            CodemirrorAsset::ADDON_EDIT_CLOSETAG,
            CodemirrorAsset::ADDON_FOLD_XML_FOLD,
            CodemirrorAsset::MODE_XML,
            CodemirrorAsset::MODE_JAVASCRIPT,
            CodemirrorAsset::MODE_CSS,
            CodemirrorAsset::MODE_HTMLMIXED,
        ],
        'settings' => [
            'lineNumbers' => true,
            'mode' => 'text/html',
            'autoCloseTags' => true,
        ],
    ]
); ?>

<?php ActiveForm::end(); ?>