<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'MMS Templates Order ' . $gender;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-posts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Wp Posts', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
                ['attribute'=> 'post_title:ntext',
                        'label' => 'Title',
                        'format' => 'raw',
                        'value' => function($model){
                            return  Html::a($model->post_title, \yii\helpers\Url::to(['wp-posts/update', 'id'=>$model->ID]));
                        }

                ],
                'mms_order',
                'post_excerpt:ntext',
                'post_type',
                'ID',
        ],
    ]); ?>

</div>
