<?php

namespace app\controllers;

use app\models\TaskSocial;
use Yii;
use yii\filters\AccessControl;

class SiteController extends Controller
{
    const LIMIT_PAGE = 18;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
//            'verbs' => [
//                'class' => VerbFilter::class,
//                'actions' => [
//                    'logout' => ['post'],
//                ],
//            ],
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
        if (Yii::$app->user->isGuest) {
            $this->layout = 'index';
            $cook = Yii::$app->getResponse()->getCookies();
            if (isset($_GET['r']) and empty($_COOKIE['refid']) and empty($_COOKIE['refurl'])) {
                $cook->add(new \yii\web\Cookie([
                    'name' => 'refid',
                    'value' => $_GET['r'],
                    'expire' => (time() + 86400 * 14)
                ]));
            }
            if (empty($_COOKIE['refurl'])) {
                $cook->add(new \yii\web\Cookie([
                    'name' => 'refurl',
                    'value' => (!empty($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '*'),
                    'expire' => (time() + 86400 * 14)
                ]));
            }
            return $this->render('index');
        }
        else {
            $this->redirect('dashboard/free-likes');
        }
    }

    public function actionLogin()
    {
        $this->layout = 'index';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('login');
    }

    public function actionAuth()
    {
        $this->layout = 'index';
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        return $this->render('login');
    }

    public function actionNetworks()
    {
        $this->layout = 'dashboard';
        return $this->render('/user/settings/networks', [
            'user' => Yii::$app->user->identity,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
//
//    public function actionContact()
//    {
//        $this->layout = 'dashboard';
//        $model = new ContactForm();
//        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
//            Yii::$app->session->setFlash('contactFormSubmitted');
//
//            return $this->refresh();
//        }
//        return $this->render('contact', [
//            'model' => $model,
//        ]);
//    }
//
    public function actionBestCoubs()
    {
        $this->layout = 'main';
        $data = TaskSocial::getBestList(self::LIMIT_PAGE);
        return $this->render('best', $data);
    }
}
