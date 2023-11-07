<?php
/*
Template Name: Create product
Template Post Type: page
*/

get_header();
?>

<div class="create_product">
    <form id="add_product_form">
        <div class="add_product_form_item">
            <label for="product_name">Product Name:</label>
            <input type="text" id="product_name" name="product_name" required>
        </div>
        <div class="add_product_form_item">
            <label for="product_price">Price:</label>
            <input type="number" id="product_price" name="product_price" min="0" required>
        </div>
        <div class="add_product_form_item">
            <label for="creation_date">Creation Date:</label>
            <input type="date" id="create_product_creation_date" name="creation_date" required>
        </div>
        <div class="add_product_form_item">
            <label for="product_type">Product Type:</label>
            <select id="create_product_product_type" name="create_product_product_type" required>
                <option value="rare">Rare</option>
                <option value="frequent">Frequent</option>
                <option value="unusual">Unusual</option>
            </select>
        </div>
        <div class="add_product_form_item">
            <label for="product_image">Product Image:</label>
            <input type="file" id="product_image" name="product_image" accept="image/*">
        </div>
        <img class="add_product_form__image" src="" alt="">
        <input type="submit" id="create_product_btn" value="Add Product">
    </form>
</div>

<script>
    // "use strict"

    // function tw_create_product_image_src() {
    //     document.addEventListener('DOMContentLoaded', function() {

    //         const inputImage = document.getElementById('product_image');

    //         const imgElement = document.querySelector('.add_product_form__image');

    //         inputImage.addEventListener('change', function() {

    //             if (this.files && this.files[0]) {
    //                 const reader = new FileReader();

    //                 reader.onload = function(e) {

    //                     imgElement.src = e.target.result;
    //                 }

    //                 reader.readAsDataURL(this.files[0]);
    //             }
    //         });
    //     });
    // };

    // tw_create_product_image_src();
</script>

<?php get_footer(); ?>