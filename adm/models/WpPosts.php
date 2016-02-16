<?php

namespace app\models;

use Yii;
use \app\models\base\WpPosts as BaseWpPosts;

/**
 * This is the model class for table "wp_posts".
 */
class WpPosts extends BaseWpPosts
{
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_author', 'mms_order', 'post_parent', 'menu_order', 'comment_count'], 'integer'],
            [['post_date', 'post_date_gmt', 'post_modified', 'post_modified_gmt'], 'safe'],
            ['post_excerpt', 'required'],
            [['post_content', 'post_title', 'post_excerpt', 'to_ping', 'pinged', 'post_content_filtered'], 'string'],
            [['post_status', 'comment_status', 'ping_status', 'post_password', 'post_type'], 'string', 'max' => 20],
            [['post_name'], 'string', 'max' => 200],
            [['guid'], 'string', 'max' => 255],
            [['post_mime_type'], 'string', 'max' => 100]
        ];
    }
	
}
