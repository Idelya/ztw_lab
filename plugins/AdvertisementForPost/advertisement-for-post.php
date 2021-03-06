<?php
/** 
 * Plugin Name:       Advertisement For Post
 * Plugin URI:        https://example.com/plugins/Advertisement For Post/ 
 * Description:       Display random advertisement under post title
 * Version:           1.0 * Requires at least: 5.0 
 * Author:            Anna Papros, Jakub Budzyński
 * Author URI:        https://darksource.pl
 * License:           GPL v2 or later 
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html 
 */

function ad_post_admin_actions_register_menu() {  
    add_options_page("Advertisement For Post", "Advertisement For Post", 'manage_options', "ad_post", "ad_post_admin_page");    
} 
 
add_action('admin_menu', 'ad_post_admin_actions_register_menu'); 

/* TODO: walidacja (długość), usuwanie ogłoszenia, losowanie i wyświetlanie,  kolejny plugin*/

function ad_post_admin_page() { 
    // get _POST variable from globals                                             
    global $_POST; 
    
    //read current option value
    $opAdList = [];
    $opAdList  =  is_array(get_option('ad_post_list')) ? get_option('ad_post_list') : [];
                

    // process new ad
    if(isset($_POST['ad_post_new']) and $_POST['ad_post_new']!=="") { 
        array_push($opAdList, $_POST['ad_post_new']);
        update_option('ad_post_list', $opAdList); 
        echo'<div class="notice notice-success is-dismissible"><p>Added new advertisment.</p></div>';     
    } 
     
    //display admin page
    ?>
    <div class="wrap">
        <h1>Advertisement For Post</h1>
        <?php 
        if (is_array($opAdList)) {
            foreach ($opAdList as $value) {
                echo "<div class='ad_post_ad_admin'>$value</div>";
            }
        }
        ?>
        <form name="ad_post_add_new_form" method="post">
            <p>New Advertisment:
                <textarea name="ad_post_new"></textarea>
            </p>
            <p class="submit">
                <input type="submit" value="Add new">
            </p>
        </form>
    </div>
    <?php
}

function ad_post_register_styles() { 
    //register style 
    wp_register_style('ad_post_styles', plugins_url('/css/style.css', __FILE__)); 
    //enable style (load in meta of html)
    wp_enqueue_style('ad_post_styles'); 
} 

add_action('init', 'ad_post_register_styles');