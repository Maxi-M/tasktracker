<?php


namespace frontend\controllers;


use yii\web\Controller;

class TaskController extends Controller
{
    public function actionView()
    {
        return $this->render('view');
    }
}