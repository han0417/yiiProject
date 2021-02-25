<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use GuzzleHttp\Client;
use app\models\Addresses;
use app\models\CountryCodes;
use yii\helpers\ArrayHelper;
use app\models\Churches;
use app\models\MassesSearch;
use app\models\UploadForm;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */

    public function actionUpload()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $model->imageFiles = UploadedFile::getInstances($model, 'imageFiles');
            if ($model->upload()) {
                // file is uploaded successfully
                return;
            }
        }

        return $this->render('upload', ['model' => $model]);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionIndex()
    {
        //檢查是否為guest
        $this->checkStatus();
        return $this->render('index');
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionView()
    {
        //檢查是否為guest
        $this->checkStatus();


        $params=Yii::$app->request->get();
        // Yii::error('params: '.print_r($params, true));
        $model = new Churches();
        $query = $model->find()->leftJoin('addresses', 'churches.address_id = addresses.id')
            ->leftJoin('priests', 'churches.priest_id = priests.id')
            ->where(['churches.id' => $params['id']])
            ->select([
                'churches.name as name', 'addresses.name as address', 'priests.name as priest', 'churches.phone as phone',
                'churches.other as other', 'churches.url as url', 'churches.img as img', 'churches.mass_info as mass_info'
            ])
            ->asArray()
            ->one();
        
        $searchModel = new MassesSearch();
        //Yii::error('queryParams: '. print_r(Yii::$app->request->queryParams, true));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', [
            'name' => $query['name'], 'priest' => $query['priest'], 'address' => $query['address'],
            'url' => $query['url'], 'img' => $query['img'], 'phone' => $query['phone'],'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    // public function actionMass()
    // {
        // $week = ['日', '一', '二', '三', '四', '五', '六'];
        // $params = Yii::$app->request->get();
        // Yii::error('gettest: '. print_r($params, true));
        // $id=$params['id'];
        // $model = new Masses();
        // $query=$model->find()
        //     ->innerJoin('languages', 'masses.languages_id = languages.id')
        //     ->innerJoin('types', 'masses.type_id = types.id')
        //     ->where(['masses.church_id' => $id, 'masses.status' => 0])
        //     ->select([
        //         'masses.week', 'masses.day', 'masses.start_time', 'masses.end_time', 'masses.notes',
        //         'types.name as type', 'languages.name as language', 'types.id as type_id'
        //     ])
        //     ->asArray()->all();
        // foreach($query as $key =>$row){
        //     $query[$key]['day']=$week[$query[$key]['day']];
        // }
        // return json_encode($query);    
    // }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionSearch()
    {

        $data = Yii::$app->request->get();

        $client = new Client();
        $url = 'https://geocode.search.hereapi.com/v1/geocode?apiKey='.Yii::$app->params['hereAPIKey'].'&q=' . $data['address'];
        $res = $client->request('GET', $url);

        $location = [];
        //將回傳資料轉為json方便後續使用
        $res = json_decode($res->getBody(), true);

        //若查無地址
        if ($res['items'] == []) {
            $location['lat'] = '';
            $location['lng'] = '';
            return $location;
        }

        $countryCode = $res['items'][0]['address']['countryCode'];
        $countryQuery = CountryCodes::find()->select(['name'])->where(['code' => $countryCode])->one();

        $location['lat'] = $res['items'][0]['position']['lat'];
        $location['lng'] = $res['items'][0]['position']['lng'];

        return json_encode($this->churchList($location, $countryQuery));
    }

    public function churchList($location, $country)
    {
        $model = new Addresses();

        $data = $model->find()
            ->innerJoin('churches', 'addresses.id = churches.address_id')
            ->where(['churches.country' => $country])
            ->select([
                'addresses.latitude', 'addresses.longitude', 'churches.id',
                'churches.name as name', 'addresses.name as address', 'churches.img as img'
            ])
            ->asArray()
            ->all();

        //計算距離
        foreach ($data as $key => $value) {
            $radLat1 = deg2rad($location['lat']); //deg2rad()函式將角度轉換為弧度
            $radLat2 = deg2rad((float)$value['latitude']);
            $radLng1 = deg2rad($location['lng']);
            $radLng2 = deg2rad((float)$value['longitude']);
            $a = $radLat1 - $radLat2;
            $b = $radLng1 - $radLng2;
            $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137;
            //計算最短距離 單位為公尺
            $data[$key]['distance'] = round($s, 3) * 1000;
        }
        //排序取五筆最近教堂
        ArrayHelper::multisort($data, 'distance', SORT_ASC);
        $recentChurches = [];
        for ($i = 0; $i <= 4; $i++) {
            $recentChurches[$i] = $data[$i];
        }

        return $recentChurches;
    }

}
