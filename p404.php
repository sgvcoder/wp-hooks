<?php

$p404_table_name = $wpdb->prefix . "p404_urls";

function p404_on_404() {

    global $wp;
    global $wpdb;
    global $p404_table_name;

    if (is_404()) {

        $url = add_query_arg($wp->query_string, '', home_url($wp->request));

        $dburl = $wpdb->get_row("SELECT * FROM $p404_table_name WHERE url = '$url'", ARRAY_A);

        if ($dburl && !empty($dburl["redirect_to"])) {

            // redirect to new url
            wp_redirect($dburl["redirect_to"], 301);
        } elseif ($dburl) {

            // update counter
            $wpdb->update($p404_table_name, array('total_view' => ($dburl["total_view"] + 1)), array('id' => $dburl["id"]));
        } else {

            // save 404 url
            $wpdb->insert($p404_table_name, array('url' => $url));
        }
    }
}

add_action('template_redirect', 'p404_on_404');

function p404_menu() {
    add_menu_page('404', '404', 'manage_options', 'p404-index', 'p404_index', 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQAQMAAAAlPW0iAAAABlBMVEX///8AAABVwtN+AAA==');
//    add_submenu_page('wpbcu-barcode-generator', 'FAQ', 'FAQ', 'manage_options', 'wpbcu-barcode-generator-faq', 'wpbcu_barcode_generator_faq');
}

add_action('admin_menu', 'p404_menu');

function p404_index() {

    $data = p404_getData();
    $pages = get_pages();
    $posts = get_posts();
    $pageTypes = p404_getPageTypes();

    require_once "view.php";
}

function p404_getData() {

    global $wpdb;
    global $p404_table_name;

    $data = $wpdb->get_results("SELECT * FROM $p404_table_name", ARRAY_A);

    return $data;
}

function p404_getPageTypes() {

    $types = array(
        array("alias" => "pages", "name" => "Pages"),
        array("alias" => "posts", "name" => "Posts")
    );

    return $types;
}

function p404_init() {

    global $wpdb;
    global $p404_table_name;

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $p404_table_name (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `url` varchar(1024) COLLATE NOT NULL,
            `redirect_to` varchar(1024) DEFAULT NULL,
            `total_view` int(11) NOT NULL DEFAULT '1',
            `added` datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY  (id)
          ) $charset_collate;";

    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
}

add_action('plugins_loaded', 'p404_init');

function p404_redirect_to_save() {

    global $wp;
    global $wpdb;
    global $p404_table_name;

    $id = intval($_POST["id"]);
    $url = strval($_POST["url"]);

    $url = strip_tags($url);
    $url = trim($url);

    // update data
    $wpdb->update($p404_table_name, array('redirect_to' => $url), array('id' => $id));

    echo json_encode(array(
        "success" => true
    ));

    wp_die();
}

add_action('wp_ajax_p404_redirect_to_save', 'p404_redirect_to_save');