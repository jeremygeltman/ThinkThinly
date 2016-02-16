<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WpPosts */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="wp-posts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'mms_order')->textInput(['placeholder' => 'Mms Order']) ?>

    <?= $form->field($model, 'post_excerpt')->textarea(['rows' => 6, 'disabled' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
