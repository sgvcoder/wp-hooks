<?php

/**
 * Create product
 * @return boolean
 */
function editWooProduct($post_id = null) {

    $post = array(
        'post_author' => 1,
        'post_content' => 'Updated Product content: ' . md5(time()),
        'post_status' => "publish",
        'post_title' => "Updated Product Name - " . uniqid(),
        'post_parent' => '',
        'post_type' => "product",
    );

    if ($post_id) {
        $post["ID"] = $post_id;
        wp_update_post($post);
    } else {
        $post_id = wp_insert_post($post);
    }

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
    update_post_meta($post_id, '_regular_price', 777);
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
    update_post_meta($post_id, '_price', 888);
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
 *
 * @param int $post_id
 * @param array $data
 */
function setProductAttributes($post_id = null, $data = null) {

    // Init temp data
    $post_id = 476;
    $data = array(
        "location" => array(
            "name" => "Location",
            "value" => "AA | BB | CC | DD",
            "position" => 0,
            "is_visible" => 1,
            "is_variation" => 1,
            "is_taxonomy" => 0
        )
    );

    // Now update the post with its new attributes
    update_post_meta($post_id, '_product_attributes', $data);
}

