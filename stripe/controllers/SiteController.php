<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use Stripe\Stripe;
use Stripe\Error;

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
        //$curl_info = curl_version();
        //echo $curl_info['tsl_version'];die;
        //echo phpinfo();die;
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (isset($_REQUEST['email']) && isset($_REQUEST['amount']) && isset($_REQUEST['token'])) {
            //GET EMAIL
            $email = $_REQUEST['email'];
            //GET TOKEN
            $card = $_REQUEST['token'];
            $grand_total = $_REQUEST['amount'];
            $grand_total = 100 * $grand_total;
            $stripe = array(
                'secret_key' => SECRET_KEY,
                'publishable_key' => PUBLISHABLE_KEY
            );
            Stripe::setApiKey($stripe['secret_key']);
            try {
                $customer = \Stripe\Customer::create(array(
                    'email' => $email,
                    'card' => $card
                ));
                $charge = \Stripe\Charge::create(array(
                    'customer' => $customer->id,
                    'amount' => $grand_total,
                    'currency' => 'usd'
                ));
                $json = json_decode(json_encode($charge), true);
                if ($json['status'] == 'succeeded') {
                    //$json['id'];
                    return array(
                        'status' => 'SUCCESS',
                        'data' => '',
                        'message' => "Payment Success",
                    );
                } else {
                    return array(
                        'status' => 'ERROR',
                        'data' => '',
                        'message' => "Payment error",
                    );
                }
            } catch (Error\InvalidRequest $e) {
                return array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => $e->getMessage(),
                );
            } catch (Error\Authentication $e) {
                return array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => $e->getMessage(),
                );
            } catch (Error\Card $e) {
                return array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => $e->getMessage(),
                );
            } catch (Error\RateLimit $e) {
                return array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => $e->getMessage(),
                );
            } catch (Error\Api $e) {
                return array(
                    'status' => 'ERROR',
                    'data' => '',
                    'message' => $e->getMessage(),
                );
            }
        } else {
            return array(
                'status' => 'ERROR',
                'data' => '',
                'message' => 'Missing Params',
            );
        }
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
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
}
