<?php


/**
 * Enqueue scripts and styles.
 */
function my_styles()
{
    wp_enqueue_script('tw-script', get_stylesheet_directory_uri() . '/asstes/js/test-work.js', array('jquery'), '1.0', true);

    wp_localize_script('tw-script', 'ajaxurl', admin_url('admin-ajax.php'));

    wp_enqueue_style('tw-style', get_stylesheet_directory_uri() . '/asstes/css/style.css');
}
add_action('wp_enqueue_scripts', 'my_styles');

/**
 * Add custom fields to product.
 */
function tw_add_custom_fields_to_product()
{
    global $post;

    if (get_post_meta($post->ID, '_creation_date', true))
    {
        $creation_date = get_post_meta($post->ID, '_creation_date', true);
    }

    //Add a field with date type
    woocommerce_wp_text_input(array(
        'id'          => 'creation_date',
        'label'       => 'Creation Date',
        'type'        => 'date',
        'desc_tip'    => 'true',
        'value'       => $creation_date,
    ));

    if (get_post_meta($post->ID, '_product_type', true))
    {
        $product_type = get_post_meta($post->ID, '_product_type', true);
    }

    //Add a field to select the product type
    woocommerce_wp_select(array(
        'id'      => 'product_type',
        'label'   => 'Product Type',
        'options' => array(
            'rare'     => 'Rare',
            'frequent' => 'Frequent',
            'unusual'  => 'Unusual',
        ),
        'value'   => $product_type,
    ));

    if (get_post_meta($post->ID, '_product_image_url', true))
    {
        $product_image_url = get_post_meta($post->ID, '_product_image_url', true);
    }

    echo "<img id='product_image' width=150px src='$product_image_url'>";
    echo '<br>';

    //Add product image btn
    echo '<input type="button" id="product_image_button" class="button" value="Set Product Image" />';
    //Add remove image btn
    echo '<input type="button" id="remove_product_image_button" class="button" value="Remove Product Image" />';
    echo '<br>';
    //Add clear all btn
    echo '<input type="button" id="clear_product_fields" class="button" value="Clear all" style="margin: 20px 8px;" />';
    echo '<br>';
    //Add custom update btn
    echo '<input type="button" id="update_product_fields" class="button" value="Update" style="margin: 20px 8px;" />';
}
add_action('woocommerce_product_options_general_product_data', 'tw_add_custom_fields_to_product');

/**
 * Custom function to display product image from custom field. 
 */
function display_custom_product_image($image, $product)
{
    $product_id = $product->get_id();
    $product_image_url = get_post_meta($product_id, '_product_image_url', true);

    if ($product_image_url)
    {
        return '<img src="' . $product_image_url . '" alt="" class="attachment-shop_catalog size-shop_catalog wp-post-image" />';
    }

    return $image;
}
add_filter('woocommerce_product_get_image', 'display_custom_product_image', 10, 2);

/**
 * Custom product image button function.
 */
function product_image_box_js()
{
?>
    <script>
        jQuery(document).ready(function($) {
            $('#product_image_button').click(function() {
                wp.media.editor.send.attachment = function(props, attachment) {
                    $('#product_image_url').val(attachment.url);
                    $('#product_image').attr('src', attachment.url);
                }

                wp.media.editor.open(this);

                return false;
            });

            $('#remove_product_image_button').click(function() {
                $('#product_image_url').val('');
                $('#product_image').removeAttr('src');
            });

            $('#clear_product_fields').click(function() {
                $('#creation_date').val('');
                $('#product_type').val('');
                $('#product_image_url').val('');
                $('#product_image').removeAttr('src');
                $('#_regular_price').val('');
            });
        });
    </script>
<?php
}
add_action('admin_footer', 'product_image_box_js');

/**
 * Ajax request from admin panel.
 */
function custom_ajax_update()
{
?>
    <script>
        document.getElementById('update_product_fields').addEventListener('click', () => {
            const creationDate = document.getElementById('creation_date').value;
            const productType = document.getElementById('product_type').value;
            const postId = document.getElementById('post_ID').value;
            const productImageUrl = document.getElementById('product_image').src;
            const regularPrice = document.getElementById('_regular_price').value;
            const title = document.getElementById('title').value;

            const formData = new FormData();
            formData.append('action', 'update_product_fields');
            formData.append('creation_date', creationDate);
            formData.append('product_type', productType);
            formData.append('product_image_url', productImageUrl);
            formData.append('post_id', postId);
            formData.append('regular_price', regularPrice);
            formData.append('title', title);

            fetch(ajaxurl, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.text();
                })
                .then(data => {
                    if (data === 'success') {
                        alert('Data updated successfully!');
                    } else {
                        alert('An error occurred while updating data.');
                    }
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        });
    </script>
<?php
}
add_action('admin_footer', 'custom_ajax_update');

/**
 * Ajax request handler.
 */
function update_product_fields()
{
    //admin panel handler
    if (isset($_POST['creation_date']) and isset($_POST['product_type']) and isset($_POST['regular_price']) and isset($_POST['title']) and isset($_POST['product_image_url']) and isset($_POST['post_id']))
    {
        $creation_date = sanitize_text_field($_POST['creation_date']);
        $post_id = sanitize_text_field($_POST['post_id']);
        $product_type = sanitize_text_field($_POST['product_type']);
        $product_image_url = sanitize_text_field($_POST['product_image_url']);
        $regular_price = sanitize_text_field($_POST['regular_price']);
        $product_title = sanitize_text_field($_POST['title']);

        update_post_meta($post_id, '_creation_date', $creation_date);
        update_post_meta($post_id, '_product_type', $product_type);
        update_post_meta($post_id, '_product_image_url', $product_image_url);
        update_post_meta($post_id, '_regular_price', $regular_price);
        update_post_meta($post_id, '_price', $regular_price);

        wp_update_post(array(
            'ID'         => $post_id,
            'post_title' => $product_title,
            'post_status' => 'publish'
        ));

        echo 'success';
    }
    //creat product handler
    elseif (isset($_POST['create_product_creation_date']) and isset($_POST['create_product_product_type']) and isset($_POST['create_product_regular_price']) and isset($_POST['create_product_title']))
    {
        $creation_date = sanitize_text_field($_POST['create_product_creation_date']);
        $product_type = sanitize_text_field($_POST['create_product_product_type']);
        $regular_price = sanitize_text_field($_POST['create_product_regular_price']);
        $product_title = sanitize_text_field($_POST['create_product_title']);


        $upload_dir = wp_upload_dir();
        $uploaded_file = $_FILES['create_product_product_image']['tmp_name'];
        $file_name = wp_unique_filename($upload_dir['path'], $_FILES['create_product_product_image']['name']);
        $file_path = $upload_dir['path'] . '/' . $file_name;

        move_uploaded_file($uploaded_file, $file_path);

        $query = new WP_Query(array(
            'post_type' => 'product',
            'post_status' => 'any',
            'posts_per_page' => -1,
        ));

        $product_exists = false;

        $post_id = 0;

        while ($query->have_posts())
        {
            $query->the_post();
            if (get_the_title() == $product_title)
            {
                $product_exists = true;
                $post_id = get_the_ID();
                break;
            }
        }

        wp_reset_postdata();

        if (!$product_exists)
        {
            $post_id = wp_insert_post(array(
                'post_title'   => $product_title,
                'post_content' => '',
                'post_type'    => 'product',
                'post_status'  => 'publish',
            ));

            update_post_meta($post_id, '_creation_date', $creation_date);
            update_post_meta($post_id, '_product_type', $product_type);
            update_post_meta($post_id, '_regular_price', $regular_price);
            update_post_meta($post_id, '_price', $regular_price);
            update_post_meta($post_id, '_product_image_url', $upload_dir['url'] . '/' . $file_name);

            echo 'success';
        }
        else
        {
            echo 'there is already a product with this name';
        }
    }
    else
    {
        echo 'error';
    }
    wp_die();
}
add_action('wp_ajax_update_product_fields', 'update_product_fields');
add_action('wp_ajax_nopriv_update_product_fields', 'update_product_fields');
