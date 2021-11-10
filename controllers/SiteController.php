<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Kegiatan;
use app\models\UploadForm;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
                    'reset' => ['post'],
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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $uploadForm = new UploadForm();
        $dataProvider = new ActiveDataProvider([
            'query' => Kegiatan::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
            */
        ]);

        if (Yii::$app->request->isPost) {
            $uploadForm->file_sieka = UploadedFile::getInstance($uploadForm, 'file_sieka');
            if ($uploadForm->upload()) {
                // file is uploaded successfully
                Yii::$app->session->setFlash('success', 'Data berhasil diupload');
            }
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'uploadForm' => $uploadForm,
        ]);
    }

    public function actionReset()
    {
        Yii::$app->db->createCommand()->truncateTable('kegiatan')->execute();
        return $this->redirect('index');
    }
}
