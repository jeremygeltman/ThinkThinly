<?php
/*
Plugin Name: Admin Meta Search
Plugin URI: http://www.perfettosites.com/portfolio/admin-meta-search/
Description: Extend Search Post, Page, Custom Post Type with custom meta value
Version: 1.0
Author: Eric Wijaya
Author URI: http://www.perfettosites.com/
*/

/*  Copyright (C) 2014 Perfettosites Inc.  (http://perfettosites.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/*
 **************************************************************
 * add filter join to post_meta
 **************************************************************
*/

add_filter('posts_join', 'perfetto_search_join' );
function perfetto_search_join ($join){
    global $pagenow, $wpdb;
    // Filter only when performing a search on edit page on all page / post / custom post type
    if ( is_admin() && $pagenow=='edit.php' && $_GET['s'] != '') {    
        $join .='LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    return $join;
}

/*
 **************************************************************
 * add filter where to post_meta
 **************************************************************
*/

add_filter( 'posts_where', 'perfetto_search_where' );
function perfetto_search_where( $where ){
    global $pagenow, $wpdb;
    // Filter only when performing a search on edit page on all page / post / custom post type
    if ( is_admin() && $pagenow=='edit.php' && $_GET['s'] != '') {
        $where = preg_replace(
       "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
       "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }
    
    return $where;
}


/* =========================================================================
 * end of program, php close tag intentionally omitted
 * ========================================================================= */
