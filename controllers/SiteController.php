<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\Product;
use app\models\Entity;
use app\models\ProductSearch;
use app\models\RequestForm;

class SiteController extends Controller
{
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

    public function actionIndex()
    {
        $productToAdd = Yii::$app->request->post('add');
        $productToRemove = Yii::$app->request->post('remove');
        
        if ($productToAdd) {
            $model = Product::getProductById($productToAdd);
            $count = Yii::$app->request->post('product-count-' . $productToAdd);
            Yii::$app->cart->put($model, abs($count));
        }
        
        if ($productToRemove) {
            $model = Product::getProductById($productToRemove);
            Yii::$app->cart->remove($model);
        }
        
        if (Yii::$app->request->post('clear')) {
            Yii::$app->cart->removeAll();
        }

        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->post(), 'Array');
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel'  => $searchModel,
            'cart' => Yii::$app->cart->getPositions(),
        ]);
    }

    public function actionLogin()
    {
        if (!\Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    public function actionAbout()
    {
        return $this->render('about');
    }
    
    public function actionRequest()
    {
        $model = new RequestForm();
        $entity = new Entity();
        $model->load(Yii::$app->request->post());
                
        if (Yii::$app->request->post('inn-search') ==='Y' && $model->validate(['inn'])) {
            $entity = Yii::$app->innFinder->search($model->inn);
        }
        
        if (Yii::$app->request->post('email-search') === 'Y' && $model->validate(['email'])) {
            $user = User::findByEmail($model->email);
        }
        
        if (Yii::$app->request->post('save') === 'Y' && $model->validate()) {
            
        }
        
        if (!Yii::$app->cart->getCount()) {
            Yii::$app->session->setFlash('danger', 'Извините, выберите один или несколько товаров, чтобы оформить заказ');
            return $this->redirect('index');    
        }
        
        return $this->render('request', [
            'model' => $model,
            'entity' => $entity,
        ]);
    }
}
