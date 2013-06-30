<?php
/**
 * Template for Moderate Categories
 * Header
 * @author Mateo Torres <torresmateo@arsisteam.com>
 */


//determine the active tab
if(isset($_GET['tab']) && in_array($_GET['tab'],array('1','2')))
    $tab = $_GET['tab']
    


?>

<div class="wrap">
    <h2 style="border-bottom: 1px solid #CCC; padding-bottom: 0px; white-space: nowrap;">
        <div id="moderate-categories-icon"></div>
        <br/>
        <a href="<?php echo get_bloginfo('url').'/wp-admin/admin.php?page=moderate-categories'?>"  class="nav-tab <?php if(!$tab) echo "nav-tab-active";?>">Main Settings</a>
    </h2>
