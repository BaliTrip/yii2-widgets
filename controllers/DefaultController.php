<?php

namespace balitrip\widgets\controllers;

use Yii;
use balitrip\widgets\models\Widgets;
use balitrip\widgets\models\WidgetsSearch;
use yii\web\NotFoundHttpException;

class DefaultController extends \yii\web\Controller
{


    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'update', 'delete','create'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'update', 'delete','create'],
                        'roles' => ['admin','widgets-admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['post','delete'],
                    //'sort' => ['post'],
                ],
            ],
        ];
    }

    public function render($view, $params = [])
    {
        \balitrip\widgets\assets\AdminAsset::register($this->view);
        return parent::render($view, $params);
    }

    public function actionIndex()
    {
        $searchModel = new WidgetsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $positions = (new \yii\db\Query())->select(['position'])->distinct()->from('{{%widgets}}')->indexBy('position')->column();

        $types = (new \yii\db\Query())->select(['widget'])->distinct()->from('{{%widgets}}')->indexBy('widget')->column();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'positions'=>$positions,
            'types'=>$types,
        ]);
    }

    public function actionCreate()
    {   
        $widgets = Yii::$app->widgets->widgetsModels;

        return $this->render('create', [
            'widgets'=>$widgets,
        ]);
        
    }

    public function actionUpdate($id = null)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->widgetModel->validate()) {

            if (Yii::$app->request->post('reload') === null && $model->save()) {

                if (Yii::$app->request->isAjax && Yii::$app->request->post('submitButton') === null) {
                    Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                    return [
                        'success' => true,
                        'model' => $model->getAttributes(),
                    ];
                }

                Yii::$app->session->setFlash('success','Widget was updated');

                return $this->redirect(['index']);
            }
            
        }

        return $this->render('update', [
            'model' => $model,
        ]);
        
    }

    public function actionSort() {

        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        $sort = Yii::$app->request->post('sort');

        $result = [];

        if ($sort !== null && is_array($sort)) {
            
            foreach ($sort as $key => $value) {
                
                $widget = $this->findModel($key);
                $widget->sort = (int)$value;
                $widget->save();

                $result[] = $widget->errors;

            }

            return [
                'success' => true,
                'result'=>$result,
            ];

        }

        return [
            'success' => false,
        ];
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {


        if ($id === null && ($widget = $this->getWidget(Yii::$app->request->get('widget'))) !== null) {
            $model = new Widgets(['state'=>1]);
            $model->widgetModel = new $widget;
            $model->widget = Yii::$app->request->get('widget');
            return $model;
        }
        elseif (($model = Widgets::findOne($id)) !== null && ($widget = $this->getWidget($model->widget)) !== null) {
            $model->widgetModel = new $widget;
            $model->widgetModel->setAttributes(\yii\helpers\Json::decode($model->params));
            return $model;
        }
        else {
          //  throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function getWidget($widget) {

        $widgetsModels = Yii::$app->widgets->widgetsModels;
        return !empty($widgetsModels[$widget]) ? $widgetsModels[$widget] : null;

    }

}
