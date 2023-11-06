<?php

/**
 * Add custom fields to product.
 */
function tw_add_custom_fields_to_product()
{
    //Add a field with date type
    woocommerce_wp_text_input(array(
        'id'          => '_creation_date',
        'label'       => 'Creation Date',
        'type'        => 'date',
        'desc_tip'    => 'true',
    ));

    //Add a field to select the product type
    woocommerce_wp_select(array(
        'id'      => '_product_type',
        'label'   => 'Product Type',
        'options' => array(
            'rare'     => 'Rare',
            'frequent' => 'Frequent',
            'unusual'  => 'Unusual',
        ),
    ));
}
add_action('woocommerce_product_options_general_product_data', 'tw_add_custom_fields_to_product');
