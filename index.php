<?php
defined('ABSPATH') or die('No script kiddies please!');

require_once __DIR__ . '/../../../wp-includes/pluggable.php';

/**
 * Plugin Name: Hooks for Wp
 * Plugin URI: https://makerise.net/
 * Description: Hooks for Wp. <a href="#custom-plugin-item"></a>
 * Version: 1.0
 * Author: Serg G.
 * Author URI: https://makerise.net/
 * License: GPLv2
 */

/**
 * Show custom notice.
 */
function sample_admin_notice__info() {
    ?>
    <div class="notice notice-warning is-dismissible">
        <a href="#" target="_blank"><img src="<?php echo plugins_url('/logo.png', __FILE__); ?>" width="125" style="margin: 10px 10px 10px 0; float: left;"/></a>
        <p>
            <?php
            _e('Text text text text text text text text text text text text text text text text text text text text text text text text text text'
                    . '<br> text text text text text text text text text<br> text text text text text'
                    . ' <a href="https://google.com" target="_blank">google</a>'
                    . ' | <a href="http://wordpress.local/wp-admin/plugin-install.php?tab=plugin-information&amp;plugin=a4-barcode-generator&amp;TB_iframe=true&amp;width=772&amp;height=618" class="thickbox open-plugin-details-modal" aria-label="More information about A4 Barcode Generator" data-title="A4 Barcode Generator">View details</a>', 'sample-text-domain');
            ?>
        </p>
    </div>
    <?php
}

add_action('admin_notices', 'sample_admin_notice__info');

/**
 * Filtering plugin list.
 */
add_filter('all_plugins', function($params) {

    if (isset($params["custom-plugin/index.php"])) {
        apply_notice($params["custom-plugin/index.php"]);
    }

    return $params;
});

function apply_notice(&$data) {

}

/**
 * Add javascript.
 */
function my_enqueue() {
    wp_enqueue_script('my_custom_script', plugins_url('/custom-script.js', __FILE__));
}

add_action('admin_enqueue_scripts', 'my_enqueue');

/**
 * Exclude this plugin from updater.
 */
add_filter('http_request_args', function ( $response, $url ) {

    if (0 === strpos($url, 'https://api.wordpress.org/plugins/update-check')) {
        $basename = plugin_basename(__FILE__);
        $plugins = json_decode($response['body']['plugins']);
        if (isset($plugins->plugins->$basename)) {
            unset($plugins->plugins->$basename);
            $active = (array) $plugins->active;
            unset($active[array_search($basename, $active)]);
            $plugins->active = (object) $active;
            $response['body']['plugins'] = json_encode($plugins);
        }
    }
    return $response;
}, 10, 2);

/**
 * Create new post
 * @return int
 */
function insertPost() {

    $post = array(
        'post_title' => 'Random Post title ' . mt_rand(1, 999999) . ' - ' . uniqid(),
        'post_content' => 'Content: ' . md5(time()),
        'post_status' => 'publish',
        'post_author' => 1,
        'post_category' => array(1)
    );

    $post_id = wp_insert_post($post);

    return $post_id;
}

/**
 * Update post
 */
function updatePost() {

    $post = array(
        'ID' => 37,
        'post_title' => 'This is the post title.',
        'post_content' => 'This is the updated content.',
    );

    wp_update_post($post);
}

/**
 * Get comments using filter.
 */
function getComments($number = 20, $offset = 0) {

    $args = array(
        'author_email' => '',
        'author__in' => '',
        'author__not_in' => '',
        'include_unapproved' => '',
        'fields' => '',
        'ID' => '',
        'comment__in' => '',
        'comment__not_in' => '',
        'karma' => '',
        'number' => $number,
        'offset' => $offset,
        'orderby' => '',
        'order' => 'DESC',
        'parent' => '',
        'post_author__in' => '',
        'post_author__not_in' => '',
        'post_ID' => '', // ignored (use post_id instead)
        'post_id' => 0,
        'post__in' => '',
        'post__not_in' => '',
        'post_author' => '',
        'post_name' => '',
        'post_parent' => '',
        'post_status' => '',
        'post_type' => '',
        'status' => 'all',
        'type' => '',
        'type__in' => '',
        'type__not_in' => '',
        'user_id' => '',
        'search' => '',
        'count' => false,
        'meta_key' => '',
        'meta_value' => '',
        'meta_query' => '',
        'date_query' => null, // See WP_Date_Query
    );

    return get_comments($args);
}

/**
 * Create new comment.
 */
function insertComment($content) {
    $time = current_time('mysql');

    $data = array(
        'comment_post_ID' => 1,
        'comment_author' => 'admin',
        'comment_author_email' => 'admin@admin.com',
        'comment_author_url' => 'http://',
        'comment_content' => $content,
        'comment_type' => '',
        'comment_parent' => 0,
        'user_id' => 1,
        'comment_author_IP' => '127.0.0.1',
        'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
        'comment_date' => $time,
        'comment_approved' => 1,
    );

    wp_insert_comment($data);
}

/**
 * Update comment.
 */
function updateComment() {
    $commentarr = array();
    $commentarr['comment_ID'] = 6;
    $commentarr['comment_approved'] = 0;
    $commentarr['comment_content'] = "content text - 2 updated!";
    wp_update_comment($commentarr);
}

/**
 * Get users by filter.
 */
function getUsers() {

    $args = array(
        'blog_id' => $GLOBALS['blog_id'],
        'role' => '',
        'role__in' => array(),
        'role__not_in' => array(),
        'meta_key' => '',
        'meta_value' => '',
        'meta_compare' => '',
        'meta_query' => array(),
        'date_query' => array(),
        'include' => array(),
        'exclude' => array(),
        'orderby' => 'login',
        'order' => 'ASC',
        'offset' => '',
        'search' => '',
        'number' => '',
        'count_total' => false,
        'fields' => 'all',
        'who' => ''
    );

    return get_users($args);
}

/**
 * Update or Insert user.
 */
function editUser() {

    $website = "http://wordpress.local-updated";
    $userdata = array(
        'ID' => 2,
        'user_login' => 'user',
        'user_url' => $website,
        'user_pass' => md5(time())
    );

    $user_id = wp_insert_user($userdata);
    wp_update_user(array('ID' => $user_id, 'role' => 'editor'));

    // On success
    if (is_wp_error($user_id)) {
        echo $return->get_error_message();
    } else {
        echo "User created : " . $user_id;
    }
}

/**
 * Get user profile.
 * @param type $user_id
 */
function getUserProfile($user_id) {

    return get_user_meta($user_id);
}

/**
 * Filtering date dropdown of Posts page.
 */
function modifyDateDropdown() {

    $screen = get_current_screen();

    if ('post' == $screen->post_type) {
        add_filter('months_dropdown_results', function($list) {

            array_push($list, (object) array(
                        "year" => 1000,
                        "month" => 1
            ));

            return $list;
        });
    }
}

add_action('admin_head', 'modifyDateDropdown');

/**
 * Get date list by posts.
 * @global type $wpdb
 * @return type
 */
function getDateDropdown() {

    global $wpdb;

    $months = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month "
                    . "FROM " . $wpdb->posts
                    . ""));
    return $months;
}

####################################################
# WooCommerce Zone
####################################################

/**
 * Create product
 * @return boolean
 */
function insertWooProduct() {
    $post = array(
        'post_author' => 1,
        'post_content' => 'Product content: ' . md5(time()),
        'post_status' => "publish",
        'post_title' => "Product Name - " . uniqid(),
        'post_parent' => '',
        'post_type' => "product",
    );

    $post_id = wp_insert_post($post);
    if (!$post_id) {
        return false;
    }

    $uploadDIR = wp_upload_dir();

    setThumbnail($post_id);

    wp_set_object_terms($post_id, 'Races', 'product_cat');
    wp_set_object_terms($post_id, 'simple', 'product_type');

    update_post_meta($post_id, '_visibility', 'visible');
    update_post_meta($post_id, '_stock_status', 'instock');
    update_post_meta($post_id, 'total_sales', '0');
    update_post_meta($post_id, '_downloadable', 'yes');
    update_post_meta($post_id, '_virtual', 'yes');
    update_post_meta($post_id, '_regular_price', mt_rand(10, 10000));
    update_post_meta($post_id, '_sale_price', "1");
    update_post_meta($post_id, '_purchase_note', "");
    update_post_meta($post_id, '_featured', "no");
    update_post_meta($post_id, '_weight', "");
    update_post_meta($post_id, '_length', "");
    update_post_meta($post_id, '_width', "");
    update_post_meta($post_id, '_height', "");
    update_post_meta($post_id, '_sku', "");
    update_post_meta($post_id, '_product_attributes', array());
    update_post_meta($post_id, '_sale_price_dates_from', "");
    update_post_meta($post_id, '_sale_price_dates_to', "");
    update_post_meta($post_id, '_price', mt_rand(10, 10000));
    update_post_meta($post_id, '_sold_individually', "");
    update_post_meta($post_id, '_manage_stock', "no");
    update_post_meta($post_id, '_backorders', "no");
    update_post_meta($post_id, '_stock', "");

    // file paths will be stored in an array keyed off md5(file path)
//    $downdloadArray = array('name' => "Test", 'file' => $uploadDIR['baseurl'] . "/video/" . $video);
//    $file_path = md5($uploadDIR['baseurl'] . "/video/" . $video);
//    $_file_paths[$file_path] = $downdloadArray;
    // grant permission to any newly added files on any existing orders for this product
    // do_action( 'woocommerce_process_product_file_download_paths', $post_id, 0, $downdloadArray );
//    update_post_meta($post_id, '_downloadable_files', $_file_paths);
    update_post_meta($post_id, '_download_limit', '');
    update_post_meta($post_id, '_download_expiry', '');
    update_post_meta($post_id, '_download_type', '');
    update_post_meta($post_id, '_product_image_gallery', '');

    return true;
}

/**
 * Set thumbnail to post
 * @param type $post_id
 */
function setThumbnail($post_id) {
    // $filename should be the path to a file in the upload directory.
    $filename = __DIR__ . "/../../../wp-content/uploads/2016/10/Adaptive-Telehealth.png";

    // The ID of the post this attachment is for.
    $parent_post_id = $post_id;

    // Check the type of file. We'll use this as the 'post_mime_type'.
    $filetype = wp_check_filetype(basename($filename), null);

    // Get the path to the upload directory.
    $wp_upload_dir = wp_upload_dir();

    // Prepare an array of post data for the attachment.
    $attachment = array(
        'guid' => $wp_upload_dir['url'] . '/' . basename($filename),
        'post_mime_type' => $filetype['type'],
        'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
        'post_content' => '',
        'post_status' => 'inherit'
    );

    // Insert the attachment.
    $attach_id = wp_insert_attachment($attachment, $filename, $parent_post_id);

    // Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
    require_once( ABSPATH . 'wp-admin/includes/image.php' );

    // Generate the metadata for the attachment, and update the database record.
    $attach_data = wp_generate_attachment_metadata($attach_id, $filename);
    wp_update_attachment_metadata($attach_id, $attach_data);

    set_post_thumbnail($parent_post_id, $attach_id);
}
