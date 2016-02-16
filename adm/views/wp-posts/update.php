<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WpPosts */

$this->title = 'Update Wp Posts: ' . ' ' . $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Wp Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->ID, 'url' => ['view', 'id' => $model->ID]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="wp-posts-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
