<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Users;

class UserController extends Controller
{
    public function actionCreate()
    {
        $model=new Users();
        $params=Yii::$app->request->post();
        $params['password']=md5($params['password']);
        $model->username=$params['username'];
        $model->password=$params['password'];
        $model->save();
        
        return 'success';
        
    }
}
