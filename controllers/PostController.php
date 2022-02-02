<?php

namespace app\controllers;

use app\components\Helpers;
use app\models\User;
use PharIo\Manifest\Author;
use Yii;
use app\models\Category;
use app\models\CategoryPost;
use app\models\Post;
use yii\helpers\Console;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;


/**
 * PostController implements the CRUD actions for Post model.
 */
class PostController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update','delete'],
                'rules' => [
                    [
                        'allow' => false,
                        'actions' => ['create', 'update','delete'],
                        'roles' => ['?'],
                        'denyCallback' => function ($rule, $action) {
                            $this->redirect(['/user/login']);
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update','delete'],
                        'roles' => ['@'],
                    ],

                ],
            ],
        ];
    }

    /**
     * Lists all Post models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = new Post();
        $model->load(Yii::$app->request->get());

        return $this->render('index', [
            'model' => $model,

        ]);
    }


    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
//            Helpers::pd($model);
        if ($model->load(Yii::$app->request->post())) {

            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Post model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Post();
        $categories = Category::find()->all();

        $model->user_id = \Yii::$app->user->identity->username;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            foreach ($model->category_ids as $item) {

                $categoryPost = new CategoryPost();
                $categoryPost->post_id = $model->id;
                $categoryPost->category_id = $item;
                if ($model->validate()) {
                    $categoryPost->save();

                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    /**
     * Updates an existing Post model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->loadCategories();

        if ($model->load(Yii::$app->request->post()))  {
        if ($model->save()) {
            $model->saveCategories();
            return $this->redirect(['index']);
        }
    }

        return $this->render('update', [
            'model' => $model,
            'categories' => Category::find()->all()
        ]);
    }

    /**
     * Deletes an existing Post model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Post model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Post the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Post::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
