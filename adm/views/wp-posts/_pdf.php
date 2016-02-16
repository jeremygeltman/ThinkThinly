<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\WpPosts */

$this->title = $model->ID;
$this->params['breadcrumbs'][] = ['label' => 'Wp Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="wp-posts-view">

    <div class="row">
        <div class="col-sm-9">
            <h2><?= 'Wp Posts'.' '. Html::encode($this->title) ?></h2>
        </div>
    </div>

    <div class="row">
<?php 
    $gridColumn = [
        'ID',
        'post_author',
        'post_date',
        'post_date_gmt',
        'post_content:ntext',
        'post_title:ntext',
        'mms_order',
        'post_excerpt:ntext',
        'post_status',
        'comment_status',
        'ping_status',
        'post_password',
        'post_name',
        'to_ping:ntext',
        'pinged:ntext',
        'post_modified',
        'post_modified_gmt',
        'post_content_filtered:ntext',
        'post_parent',
        'guid',
        'menu_order',
        'post_type',
        'post_mime_type',
        'comment_count',
    ];
    echo DetailView::widget([
        'model' => $model,
        'attributes' => $gridColumn
    ]); 
?>
    </div>
</div>