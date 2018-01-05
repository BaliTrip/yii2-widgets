<?php

namespace balitrip\widgets\models;

use Yii;


class Widgets extends \yii\db\ActiveRecord
{
    public $widgetModel;

    private $bound;

    public static function tableName()
    {
        return '{{%widgets}}';
    }
    
    public function rules()
    {
        return [
            [['widget', 'name','position'], 'required','when'=>function($model){return Yii::$app->request->post('reload') === null;}, 'whenClient' => "function (attribute, value) { return $('input[name=\"reload\"]').length == 0; }"],
            [['params'], 'string'],
            [['widget', 'name','css_class','position'], 'string','max'=>255],
            [['lang'], 'string','max'=>255,'skipOnEmpty'=>true],
            [['state','sort','cache'],'integer'],
            ['bound','safe'],
            ['state','default','value'=>1],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'name' => 'Name',
            'position' => 'Position',
            'bound' => 'Bound',
            'params' => 'Params',
            'cache' => 'Cache',
            'lang' => 'Язык',
            'css_class' => 'CSS Class',
        ];
    }

    public function behaviors()
    {
        return [
            \yii\behaviors\TimestampBehavior::className(),
        ];
    }

    public function load($data, $formName = null)
    {
        if ($this->widgetModel !== null) {
            $this->widgetModel->load($data, $formName);
            return parent::load($data, $formName);
        }
        else {
            return parent::load($data, $formName);
        }
    }

    public function getBounds() {
        return $this->hasMany(WidgetBounds::className(),['widget_id'=>'id']);
    }

    public function getBound() {
        if ($this->bound === null && !$this->isNewRecord) {
            $this->bound = (new \yii\db\Query())->select(['module','controller','action','except'])
                ->from('{{%widget_bounds}}')
                ->where(['widget_id'=>$this->id])
                ->all();
        }
        return $this->bound;
    }

    public function setBound($a) {
        if (is_array($a)) 
            foreach ($a as $key => $value) {
                if (empty($value['module']) && empty($value['controller']) && empty($value['action'])) {
                    unset($a[$key]);
                }
            }
        return $this->bound = $a;
        
    }

    public function getParams() {

        return $this->params !== null ? \yii\helpers\Json::decode($this->params) : null;
        
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->params = \yii\helpers\Json::encode($this->widgetModel->getAttributes());
            return true;
        } else {
            return false;
        }
    }

    public function afterSave($insert, $changedAttributes)
    {


        Yii::$app->db->createCommand()->delete('{{%widget_bounds}}', ['widget_id'=>$this->id])->execute();

        if (is_array($this->bound)) {
            foreach ($this->bound as $key => $value) {
                Yii::$app->db->createCommand()->insert('{{%widget_bounds}}',[
                    'widget_id'=>$this->id, 
                    'module'=>!empty($value['module'])?$value['module']:null,
                    'controller'=>!empty($value['controller'])?$value['controller']:null,
                    'action'=>!empty($value['action'])?$value['action']:null,
                    'except'=>!empty($value['except'])?$value['except']:null,
                ])->execute();
            }
        }

        return parent::afterSave($insert, $changedAttributes);
    } 

    public function afterDelete()
    {
        Yii::$app->db->createCommand()->delete('{{%widget_bounds}}', ['widget_id'=>$this->id])->execute();
        parent::afterDelete();
        
    }

}
