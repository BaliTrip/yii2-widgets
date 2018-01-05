<?php

namespace balitrip\widgets\models;

use Yii;

/**
 * This is the model class for table "{{%widget_bounds}}".
 *
 * @property integer $id
 * @property string $module
 * @property string $controller
 * @property string $action
 * @property integer $widget_id
 * @property integer $except
 */
class WidgetBounds extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%widget_bounds}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['widget_id'], 'required'],
            [['widget_id', 'except'], 'integer'],
            [['module', 'controller', 'action'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'module' => 'Module',
            'controller' => 'Controller',
            'action' => 'Action',
            'widget_id' => 'Widget ID',
            'except' => 'Except',
        ];
    }
}
