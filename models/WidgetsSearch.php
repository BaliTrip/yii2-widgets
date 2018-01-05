<?php

namespace balitrip\widgets\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class WidgetsSearch extends Widgets
{
    public $positions;

    public function rules()
    {
        return [
            [['widget', 'name','position','lang'], 'string','max'=>255],
        ];
    }

    public function query() {
        return Widgets::find();
    }

    public function search($params)
    {
        $query = Widgets::find()->orderBy('sort');


        $dataProvider = new ActiveDataProvider([
            'query' => $query, 
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            //return $dataProvider;
        }

        if (!empty($this->lang)) {
            $query->andWhere([
                'lang' => [$this->lang,'',null],
            ]);
        }

        $query->andFilterWhere([
            'widget' => $this->widget,
            'position' => $this->position,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
