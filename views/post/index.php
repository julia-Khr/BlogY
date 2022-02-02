<?php


use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = Yii::t('app', 'Posts');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Create Post'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $model->search(),
        'filterModel' => $model,
        'columns' => [
            'id',
            'title',
            [
                'attribute' => 'date',
                'format' => 'date',
                'filter' => false,

            ],
            'user_id',
            [
                'attribute' => 'category_ids',
                'content' => function ($data) {

                    $categories = "";
                    foreach ($data->categoryPosts as $item) {
                        $categories .= $item->category['title'] . "<br>";

                    }
                    return $categories;
                },
                'filter' => \app\models\Category::listCategory(),
                'value' => $model->category_ids,
//                                    \app\components\Helpers::pd(\app\models\CategoryPost::find())
            ],
            ['class' => 'yii\grid\ActionColumn', 'template' => '{view} {delete}']
        ],
    ]) ?>


</div>
