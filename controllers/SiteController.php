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
use app\models\User;
use app\models\Request;
use app\models\Customer;
use app\models\RequestToProduct;

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
            Yii::$app->session->setFlash('success', 'Вы успешно авторизованы');
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        if (Yii::$app->user->logout()) {
            Yii::$app->session->setFlash('success', 'Вы успешно вышли');
        }

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
    
    public function actionConfirm()
    {
        $model = new RequestForm();
        $entity = Yii::$app->user->isGuest? (new Entity()): Yii::$app->user->identity->getEntityFirst();
        $user = Yii::$app->user->isGuest? (new User()): Yii::$app->user->identity;
        $model->load(Yii::$app->request->post());
        
        if (!empty($model->inn) && $model->validate(['inn'])) {
            $entity = Yii::$app->innFinder->search($model->inn);
            if (!$entity) {
                $model->addError('inn', 'Не могу найти организацию или ИП по данному ИНН');
            }
        }
        
        if (!empty($model->email) && $model->validate(['email'])) {
            $user = User::searchByEmail($model->email);
            if (!$user) {
                $model->addError('email', 'Не могу проверить валидность данного адреса');
            }
        }
        
        if (isset($user->email) && empty($model->email)) $model->email = $user->email;
        if (isset($entity->id) && empty($model->inn)) $model->inn = $entity->inn;
        
        if (!Yii::$app->cart->getCount()) {
            Yii::$app->session->setFlash('danger', 'Извините, выберите один или несколько товаров, чтобы оформить заказ');
            return $this->redirect('index');    
        }
        
        if (Yii::$app->request->post('save') === 'Y' && $model->validate() && $user->save()) {
            if ($user->getCustomers()->exists()) {
                $customer = $user->getCustomers()->one();
            } else {
                $customer = new Customer();
                $customer->name = $user->email;
                $customer->save();
            }
            if (!$customer->getEntities()->where(['id' => $entity->id])->exists()) {
                $customer->link('entities', $entity);
            }
            $request = new Request();
            $request->status = Request::STATUS_ACTIVE;
            $request->customer = $customer->id;
            $request->save();
            foreach (Yii::$app->cart->getPositions() as $cartPosition) {
                $requestToProduct = new RequestToProduct();
                $requestToProduct->request = $request->id;
                $requestToProduct->product = $cartPosition->id;
                $requestToProduct->quantity = $cartPosition->getQuantity();
                $requestToProduct->save();
            }
            Yii::$app->cart->removeAll();
            Yii::$app->session->setFlash('success', '<strong>Ваш запрос успешно размещен</strong>,<br> Мы постараемся отправить предложение как можно скорее');
            return $this->redirect('/');
        }
        
        return $this->render('confirm', [
            'model' => $model,
            'entity' => $entity,
            'user' => $user,
            'cart' => Yii::$app->cart->getPositions(),
        ]);
    }
}
