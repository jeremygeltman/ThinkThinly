<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\WpPosts */

$this->title = 'Create Wp Posts';
$this->params['breadcrumbs'][] = ['label' => 'Wp Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-posts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
