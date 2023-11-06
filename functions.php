<?php

/**
 * Add custom fields to product.
 */
function tw_add_custom_fields_to_product()
{
    //Add a field with date type
    woocommerce_wp_text_input(array(
        'id'          => 'creation_date',
        'label'       => 'Creation Date',
        'type'        => 'date',
        'desc_tip'    => 'true',
    ));

    //Add a field to select the product type
    woocommerce_wp_select(array(
        'id'      => 'product_type',
        'label'   => 'Product Type',
        'options' => array(
            'rare'     => 'Rare',
            'frequent' => 'Frequent',
            'unusual'  => 'Unusual',
        ),
    ));

    echo "<img id='product_image' width=150px src=''>";
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
    if (isset($_POST['creation_date']) and isset($_POST['post_id']) and isset($_POST['product_type']) and isset($_POST['product_image_url']) and isset($_POST['title']))
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

        wp_update_post(array(
            'ID'         => $post_id,
            'post_title' => $product_title
        ));

        echo 'success';
    }
    else
    {
        echo 'error';
    }
    wp_die();
}

add_action('wp_ajax_update_product_fields', 'update_product_fields');
