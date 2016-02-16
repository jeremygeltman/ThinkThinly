<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Wp Posts';
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
            ['class' => 'yii\grid\SerialColumn'],

            'ID',
            'post_author',
            'post_date',
            'post_date_gmt',
            'post_content:ntext',
            // 'post_title:ntext',
            // 'mms_order',
            // 'post_excerpt:ntext',
            // 'post_status',
            // 'comment_status',
            // 'ping_status',
            // 'post_password',
            // 'post_name',
            // 'to_ping:ntext',
            // 'pinged:ntext',
            // 'post_modified',
            // 'post_modified_gmt',
            // 'post_content_filtered:ntext',
            // 'post_parent',
            // 'guid',
            // 'menu_order',
            // 'post_type',
            // 'post_mime_type',
            // 'comment_count',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
