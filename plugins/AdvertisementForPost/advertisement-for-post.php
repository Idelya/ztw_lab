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
    add_menu_page("Manage advertisements", "Ads", 'manage_options', "add_adv", "ad_post_admin_page", "dashicons-welcome-widgets-menus");    
} 
add_action('admin_menu', 'ad_post_admin_actions_register_menu'); 



/* TODO: walidacja (długość), usuwanie ogłoszenia, losowanie i wyświetlanie,  kolejny plugin*/



function ad_post_admin_page() { 
                                          
    global $_POST; 
    $opAdList  =  is_array(get_option('ad_post_list')) ? get_option('ad_post_list') : [];
                
    if(isset($_POST['id_value']) and $_POST['id_value']!=="") {
        //delete_option('ad_post_list');
        unset($opAdList[$_POST['id_value']]);
        update_option('ad_post_list', $opAdList); 
        echo'<div class="notice notice-error is-dismissible"><p>Deleted advertisment.</p></div>';    
    } 

    if(isset($_POST['ad_post_new']) and $_POST['ad_post_new']!=="") { 
        array_push($opAdList, $_POST['ad_post_new']);
        update_option('ad_post_list', $opAdList); 
        echo'<div class="notice notice-success is-dismissible"><p>Added new advertisment.</p></div>';     
    }

    ?>
    <div class="wrap center">
        <form name="ad_post_add_new_form" method="post">
            <div style="width: 100%;">
                <div >
                    <h1 class="title">New Advertisment</h1>
                    <textarea name="ad_post_new" class="insertText" type="text"></textarea>
                </div>
                <p class="submitButton">
                    <button type="submit" style="margin: 0 auto;">Add new</button>
                </p>
            </div>
        </form>
        <h1 class="title">Advertisement For Post</h1>
        <div class="spaceAds">
        <?php foreach ($opAdList as $key=>$value) : ?>
            <form class='element' method='post' name='delete_post'>
                <div class='content'>
                    <?= $value ?>
                </div>
                <input type='hidden' id='id_value' name='id_value' value=<?= $key ?> >
                <button class='deleteButton' type='submit'>DELETE</button>
            </form>
        <?php endforeach ?>    
        </div>
    </div>
    <?php
}



function insertBeforeContent($content){
    if(is_singular() && in_the_loop() && is_main_query()){
        $opAdList  =  is_array(get_option('ad_post_list')) ? get_option('ad_post_list') : [];
        $randomElement = $opAdList[array_rand($opAdList, 1)];
        $custom = "<div class='center' style='padding: 2rem; border: solid gray 2px; width: 100%'>$randomElement</div>";
        return $custom.$content;
    }
    return $content;
}
add_filter('the_content', "insertBeforeContent");



function ad_post_register_styles() { 
    wp_register_style('ad_post_styles', plugins_url('/css/style.css', __FILE__)); 
    wp_enqueue_style('ad_post_styles'); 
} 
add_action('init', 'ad_post_register_styles');