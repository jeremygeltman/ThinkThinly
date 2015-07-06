<?php  
$title = single_post_title('',false);
if (empty($title)) { 
    ?>Post ID <?php the_ID(); 
} else { 
    single_post_title();
}                              
?>