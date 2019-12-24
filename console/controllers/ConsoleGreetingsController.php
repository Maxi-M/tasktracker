<?php


namespace console\controllers;


use yii\console\Controller;
use yii\console\ExitCode;

class ConsoleGreetingsController extends Controller
{
    public function actionIndex()
    {
        echo 'Hello, World!';
        return ExitCode::OK;
    }
}