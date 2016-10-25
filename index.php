<?php
defined('ABSPATH') or die('No script kiddies please!');

require_once __DIR__ . '/../../../wp-includes/pluggable.php';

/**
 * Plugin Name: Custom Plugin
 * Plugin URI: http://wordpress.local/
 * Description: Description. <a href="#custom-plugin-item"></a>
 * Version: 1.0
 * Author: Plugin Author's Name
 * Author URI: Author's website
 * License: GPLv2
 */
// add notice
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

// filtering plugin list
add_filter('all_plugins', function($params) {

    if (isset($params["custom-plugin/index.php"])) {
        apply_notice($params["custom-plugin/index.php"]);
    }
//    echo "<pre>";
//    print_r($params);
//    exit;

    return $params;
});

function apply_notice(&$data) {

}

// add javascript
function my_enqueue() {
    wp_enqueue_script('my_custom_script', plugins_url('/custom-script.js', __FILE__));
}

add_action('admin_enqueue_scripts', 'my_enqueue');

// exclude this plugin from updater
add_filter('http_request_args', function ( $response, $url ) {

    if (0 === strpos($url, 'https://api.wordpress.org/plugins/update-check')) {
        $basename = plugin_basename(__FILE__);
        $plugins = json_decode($response['body']['plugins']);
        if (isset($plugins->plugins->$basename)) {
            unset($plugins->plugins->$basename);
            unset($plugins->active[array_search($basename, $plugins->active)]);
            $response['body']['plugins'] = json_encode($plugins);
        }
    }
    return $response;
}, 10, 2);

function getComments() {

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
        'number' => '',
        'offset' => '',
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
    $c = get_comments($args);
    print_r($c);

    exit;
}

//getComments();

function insertComment() {
    $time = current_time('mysql');

    $data = array(
        'comment_post_ID' => 1,
        'comment_author' => 'admin',
        'comment_author_email' => 'admin@admin.com',
        'comment_author_url' => 'http://',
        'comment_content' => 'content here 2',
        'comment_type' => '',
        'comment_parent' => 3,
        'user_id' => 1,
        'comment_author_IP' => '127.0.0.1',
        'comment_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.10) Gecko/2009042316 Firefox/3.0.10 (.NET CLR 3.5.30729)',
        'comment_date' => $time,
        'comment_approved' => 1,
    );

    wp_insert_comment($data);
}

function updateComment() {
    $commentarr = array();
    $commentarr['comment_ID'] = 6;
    $commentarr['comment_approved'] = 0;
    $commentarr['comment_content'] = "content text - 2 updated!";
    wp_update_comment($commentarr);
}

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
    $u = get_users($args);

    echo "<pre>";
    print_r($u);
    exit;
}

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

    //On success
    if (is_wp_error($user_id)) {
        echo $return->get_error_message();
    } else {
        echo "User created : " . $user_id;
    }
}

function getUserProfile() {

    $all_meta_for_user = get_user_meta(1);
    echo "<pre>";
    print_r($all_meta_for_user);
    exit;
}

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

function getDateDropdown() {

    global $wpdb;

    $months = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month "
                    . "FROM " . $wpdb->posts
                    . ""));
    return $months;
}
error_reporting(E_ALL);
ini_set("display_errors", "On");


echo "<pre>";
print_r(get_pages());
exit;
