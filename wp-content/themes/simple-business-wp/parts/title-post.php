<?php 
$title = the_title('','',false);
if (empty($title)) { 
?>Post ID <?php 
the_ID(); 
} else { 
    the_title();
}                              
?>