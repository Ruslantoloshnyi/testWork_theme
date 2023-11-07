<?php

get_header();
?>

<main>
    <div class="container">
        <div class="front_product">

            <?php
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
            );

            $products = new WP_Query($args);

            if ($products->have_posts())
            {
                while ($products->have_posts())
                {
                    $products->the_post();
                    $product_id = get_the_ID();
                    $product_image = get_post_meta($product_id, '_product_image_url', true);
                    $product_title = get_the_title();
                    $creation_date = get_post_meta($product_id, '_creation_date', true);
                    $product_type = get_post_meta($product_id, '_product_type', true);
                    $regular_price = get_post_meta($product_id, '_regular_price', true);
            ?>

                    <div class="front_product_card">
                        <div class="front_product_card__image">
                            <img src="<?php echo $product_image; ?>" alt="">
                        </div>
                        <div class="front_product_card_wrapper">
                            <div class="front_product_card__name">
                                <h3><?php echo $product_title; ?></h3>
                            </div>
                            <div class="front_product_card__date"><?php echo $creation_date; ?></div>
                            <div class="front_product_card__type"><?php echo $product_type; ?></div>
                            <div class="front_product_card__price"><?php echo $regular_price; ?> $</div>
                            <div class="front_product_card__btn">
                                <a href="#">to cart</a>
                            </div>
                        </div>
                    </div>

            <?php
                }
                wp_reset_postdata();
            }
            ?>
        </div>
    </div>
</main>

<?php get_footer(); ?>