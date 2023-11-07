"use strict"

// download image to img src
function tw_create_product_image_src() {
    document.addEventListener('DOMContentLoaded', function () {

        const inputImage = document.getElementById('product_image');

        const imgElement = document.querySelector('.add_product_form__image');

        inputImage.addEventListener('change', function () {

            if (this.files && this.files[0]) {
                const reader = new FileReader();

                reader.onload = function (e) {

                    imgElement.src = e.target.result;
                }

                reader.readAsDataURL(this.files[0]);
            }
        });
    });
};

tw_create_product_image_src();

// ajax create product request
function tw_front_ajax_request() {
    document.getElementById('create_product_btn').addEventListener('click', (event) => {
        let productName = document.getElementById('product_name').value;
        let productPrice = document.getElementById('product_price').value;
        let createProductCreationDate = document.getElementById('create_product_creation_date').value;
        let createProductProductType = document.getElementById('create_product_product_type').value;
        let createProductProductImage = document.getElementById('product_image').files[0];

        event.preventDefault();

        const formData = new FormData();
        formData.append('action', 'update_product_fields');
        formData.append('create_product_creation_date', createProductCreationDate);
        formData.append('create_product_product_type', createProductProductType);
        formData.append('create_product_regular_price', productPrice);
        formData.append('create_product_title', productName);
        formData.append('create_product_product_image', createProductProductImage);

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
                    document.getElementById('product_name').value = '';
                    document.getElementById('product_price').value = '';
                    document.getElementById('create_product_creation_date').value = '';
                    document.getElementById('create_product_product_type').value = '';
                    document.querySelector('.add_product_form__image').src = '';

                    alert('Product successfully registered!');
                }
                else if (data === 'there is already a product with this name') {
                    alert('there is already a product with this name');
                }
                else {
                    alert('An error occurred while updating data.');
                }
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
            });

    });
};

tw_front_ajax_request();







