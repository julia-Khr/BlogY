<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $model app\models\Post */
/* @var $model app\models\Category */
/* @var $categories app\models\Category [] */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="post-form">
   
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'category_ids')->checkBoxList(
        ArrayHelper::map($categories, 'id', 'title')
    ) ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
