<?php
require_once '../../core/Middleware.php';

Middleware::admin();
$pageTitle = "Edit Product";
$productId = $_GET['id'] ?? 1;

$productName = "Classic Sneakers";
$productCategory = "1";
$productPrice = "4500";
$productStock = "25";
$productStatus = "active";
$productDescription = "Comfortable and stylish sneakers suitable for everyday use.";

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="utf-8">

<meta name="viewport"
    content="width=device-width, initial-scale=1, shrink-to-fit=no">

<link
    rel="apple-touch-icon"
    sizes="76x76"
    href="../assets/img/apple-icon.png"
>

<link
    rel="icon"
    type="image/png"
    href="../assets/img/favicon.png"
>

<title>Edit Product - ClothWear</title>


<!-- Fonts -->

<link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900"
>


<!-- Nucleo Icons -->

<link
    href="../assets/css/nucleo-icons.css"
    rel="stylesheet"
>

<link
    href="../assets/css/nucleo-svg.css"
    rel="stylesheet"
>


<!-- Font Awesome -->

<link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
>


<!-- Material Icons -->

<link
    rel="stylesheet"
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0"
>


<!-- Material Dashboard CSS -->

<link
    id="pagestyle"
    href="../assets/css/material-dashboard.css?v=3.2.0"
    rel="stylesheet"
>


<!-- =========================
     CUSTOM PRODUCT FORM CSS
========================== -->

<style>

    /* =========================
       FORM CARD
    ========================== */

    .product-form-card {
        border-radius: 16px;
        overflow: hidden;
    }


    /* =========================
       CARD HEADER
    ========================== */

    .product-form-header {
        padding: 28px 30px 18px;
        border-bottom: 1px solid #e9ecef;
    }

    .product-form-header h5 {
        font-weight: 700;
        color: #212529;
    }

    .product-form-header p {
        margin-bottom: 0;
        color: #67748e;
    }


    /* =========================
       FORM BODY
    ========================== */

    .product-form-body {
        padding: 30px;
    }


    /* =========================
       FORM FIELD
    ========================== */

    .product-field {
        margin-bottom: 28px;
    }


    /* =========================
       LABEL
    ========================== */

    .product-field label {
        display: flex;
        align-items: center;
        gap: 8px;

        margin-bottom: 10px;

        font-size: 14px;
        font-weight: 700;

        color: #344767;
    }


    /* Label Icon */

    .product-field label i {
        font-size: 14px;
         color: #242426;
    }


    /* =========================
       INPUT / SELECT / TEXTAREA
    ========================== */

    .product-input {
        width: 100%;
        min-height: 48px;

        padding: 12px 15px;

        border: 1px solid #d8dce3;
        border-radius: 8px;

        background-color: #ffffff;

        font-size: 14px;
        color: #344767;

        transition: all 0.2s ease;

        box-shadow: none;
    }


    /* Placeholder */

    .product-input::placeholder {
        color: #adb5bd;
    }


    /* =========================
       INPUT FOCUS
    ========================== */

    .product-input:focus {

        border-color: #2e37a4;

        box-shadow:
            0 0 0 3px rgba(46, 55, 164, 0.10);

        outline: none;
    }


    /* =========================
       SELECT
    ========================== */

    select.product-input {
        cursor: pointer;
    }


    /* =========================
       TEXTAREA
    ========================== */

    textarea.product-input {
        min-height: 130px;
        resize: vertical;
    }


    /* =========================
       FILE INPUT
    ========================== */

    input[type="file"].product-input {
        padding: 10px 12px;
        cursor: pointer;
    }


    /* =========================
       HELPER TEXT
    ========================== */

    .field-help {
        display: block;

        margin-top: 7px;

        font-size: 12px;

        color: #8392ab;
    }


    /* =========================
       PRODUCT ID INFO
    ========================== */

    .product-id-info {

        display: inline-flex;

        align-items: center;

        gap: 6px;

        margin-top: 5px;

        padding: 5px 10px;

        border-radius: 6px;

        background-color: #f8f9fa;

        color: #8392ab;

        font-size: 11px;

        font-weight: 600;
    }


    /* =========================
       BUTTON AREA
    ========================== */

    .product-form-actions {

        display: flex;

        justify-content: flex-end;

        gap: 10px;

        padding-top: 20px;

        margin-top: 5px;

        border-top: 1px solid #e9ecef;
    }


    /* =========================
       UPDATE BUTTON
    ========================== */

    .product-form-actions .update-btn {

        min-width: 160px;

        padding: 11px 18px;

        font-weight: 600;
    }


    /* =========================
       CANCEL BUTTON
    ========================== */

    .product-form-actions .cancel-btn {

        min-width: 100px;

        padding: 11px 18px;

        font-weight: 600;
    }


    /* =========================
       MOBILE
    ========================== */

    @media (max-width: 576px) {

        .product-form-body {
            padding: 20px;
        }


        .product-form-header {
            padding: 22px 20px 16px;
        }


        .product-form-actions {
            flex-direction: column-reverse;
        }


        .product-form-actions .btn {
            width: 100%;
        }

    }

</style>

</head>

<body class="g-sidenav-show bg-gray-100">

<!-- =========================
     ADMIN HEADER
========================== -->

<?php require_once '../../includes/admin-header.php'; ?>


<!-- =========================
     ADMIN SIDEBAR
========================== -->

<?php require_once '../../includes/admin-sidenavbar.php'; ?>


<!-- =========================
     MAIN CONTENT
========================== -->

<main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">


    <div class="container-fluid py-4">


        <!-- =========================
             PAGE HEADER
        ========================== -->

        <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">

            <div>

                <h4 class="fw-bold text-dark mb-1">
                    Edit Product
                </h4>

                <p class="text-sm text-secondary mb-0">
                    Update the product information
                </p>

            </div>


            <!-- BACK BUTTON -->

            <a
                href="index.php"
                class="btn btn-outline-secondary btn-sm"
            >

                <i class="fa-solid fa-arrow-left me-1"></i>

                Back to Products

            </a>

        </div>


        <!-- =========================
             PRODUCT FORM
        ========================== -->

        <div class="row">

            <div class="col-lg-10 col-md-11 mx-auto">


                <!-- FORM CARD -->

                <div class="card product-form-card">


                    <!-- =========================
                         CARD HEADER
                    ========================== -->

                    <div class="product-form-header">

                        <h5 class="mb-1">
                            Product Information
                        </h5>

                        <p class="text-sm">
                            Update the details of this product.
                        </p>


                        <!-- PRODUCT ID -->

                        <span class="product-id-info">

                            <i class="fa-solid fa-hashtag"></i>

                            Product ID:
                            <?php echo htmlspecialchars($productId); ?>

                        </span>

                    </div>


                    <!-- =========================
                         FORM BODY
                    ========================== -->

                    <div class="product-form-body">


                        <form
                            method="POST"
                            action=""
                            enctype="multipart/form-data"
                        >


                            <!-- Hidden Product ID -->

                            <input
                                type="hidden"
                                name="product_id"
                                value="<?php echo htmlspecialchars($productId); ?>"
                            >


                            <!-- =========================
                                 PRODUCT NAME
                            ========================== -->

                            <div class="product-field">

                                <label for="product_name">

                                    <i class="fa-solid fa-box"></i>

                                    Product Name

                                </label>


                                <input
                                    type="text"
                                    id="product_name"
                                    name="product_name"
                                    class="product-input"
                                    value="<?php echo htmlspecialchars($productName); ?>"
                                    placeholder="Enter product name"
                                    required
                                >


                                <span class="field-help">
                                    Update the name of this product.
                                </span>

                            </div>


                            <!-- =========================
                                 CATEGORY + PRICE
                            ========================== -->

                            <div class="row">


                                <!-- CATEGORY -->

                                <div class="col-md-6">

                                    <div class="product-field">

                                        <label for="category">

                                            <i class="fa-solid fa-layer-group"></i>

                                            Category

                                        </label>


                                        <select
                                            id="category"
                                            name="category"
                                            class="product-input"
                                            required
                                        >

                                            <option value="">
                                                Select Category
                                            </option>


                                            <option
                                                value="1"
                                                <?php echo ($productCategory === "1") ? "selected" : ""; ?>
                                            >
                                                Footwear
                                            </option>


                                            <option
                                                value="2"
                                                <?php echo ($productCategory === "2") ? "selected" : ""; ?>
                                            >
                                                Clothing
                                            </option>


                                            <option
                                                value="3"
                                                <?php echo ($productCategory === "3") ? "selected" : ""; ?>
                                            >
                                                Accessories
                                            </option>


                                            <option
                                                value="4"
                                                <?php echo ($productCategory === "4") ? "selected" : ""; ?>
                                            >
                                                Bags
                                            </option>

                                        </select>


                                        <span class="field-help">
                                            Select the category for this product.
                                        </span>

                                    </div>

                                </div>


                                <!-- PRICE -->

                                <div class="col-md-6">

                                    <div class="product-field">

                                        <label for="price">

                                            <i class="fa-solid fa-money-bill"></i>

                                            Price

                                        </label>


                                        <input
                                            type="number"
                                            id="price"
                                            name="price"
                                            class="product-input"
                                            value="<?php echo htmlspecialchars($productPrice); ?>"
                                            placeholder="Enter price"
                                            min="0"
                                            step="0.01"
                                            required
                                        >


                                        <span class="field-help">
                                            Update the product price.
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <!-- =========================
                                 STOCK + STATUS
                            ========================== -->

                            <div class="row">


                                <!-- STOCK -->

                                <div class="col-md-6">

                                    <div class="product-field">

                                        <label for="stock">

                                            <i class="fa-solid fa-boxes-stacked"></i>

                                            Stock Quantity

                                        </label>


                                        <input
                                            type="number"
                                            id="stock"
                                            name="stock"
                                            class="product-input"
                                            value="<?php echo htmlspecialchars($productStock); ?>"
                                            placeholder="Enter stock quantity"
                                            min="0"
                                            required
                                        >


                                        <span class="field-help">
                                            Update the available quantity.
                                        </span>

                                    </div>

                                </div>


                                <!-- STATUS -->

                                <div class="col-md-6">

                                    <div class="product-field">

                                        <label for="status">

                                            <i class="fa-solid fa-circle-check"></i>

                                            Product Status

                                        </label>


                                        <select
                                            id="status"
                                            name="status"
                                            class="product-input"
                                            required
                                        >

                                            <option
                                                value="active"
                                                <?php echo ($productStatus === "active") ? "selected" : ""; ?>
                                            >
                                                Active
                                            </option>


                                            <option
                                                value="inactive"
                                                <?php echo ($productStatus === "inactive") ? "selected" : ""; ?>
                                            >
                                                Inactive
                                            </option>

                                        </select>


                                        <span class="field-help">
                                            Change whether this product is currently active.
                                        </span>

                                    </div>

                                </div>

                            </div>


                            <!-- =========================
                                 DESCRIPTION
                            ========================== -->

                            <div class="product-field">

                                <label for="description">

                                    <i class="fa-solid fa-align-left"></i>

                                    Product Description

                                </label>


                                <textarea
                                    id="description"
                                    name="description"
                                    class="product-input"
                                    rows="5"
                                    placeholder="Enter product description"
                                ><?php echo htmlspecialchars($productDescription); ?></textarea>


                                <span class="field-help">
                                    Update the product description.
                                </span>

                            </div>


                            <!-- =========================
                                 PRODUCT IMAGE
                            ========================== -->

                            <div class="product-field">

                                <label for="image">

                                    <i class="fa-solid fa-image"></i>

                                    Product Image

                                </label>


                                <input
                                    type="file"
                                    id="image"
                                    name="image"
                                    class="product-input"
                                    accept="image/jpeg,image/png,image/webp"
                                >


                                <span class="field-help">
                                    Upload a new JPG, JPEG, PNG or WEBP image.
                                    Leave empty to keep the current image.
                                </span>

                            </div>


                            <!-- =========================
                                 BUTTONS
                            ========================== -->

                            <div class="product-form-actions">


                                <!-- CANCEL -->

                                <a
                                    href="index.php"
                                    class="btn btn-light cancel-btn"
                                >

                                    <i class="fa-solid fa-xmark me-1"></i>

                                    Cancel

                                </a>


                                <!-- UPDATE -->

                                <button
                                    type="submit"
                                    name="update_product"
                                    class="btn bg-gradient-dark update-btn"
                                >

                                    <i class="fa-solid fa-pen-to-square me-1"></i>

                                    Update Product

                                </button>


                            </div>


                        </form>

                    </div>

                </div>

            </div>

        </div>


        <!-- =========================
             ADMIN FOOTER
        ========================== -->

        <?php require_once '../../includes/admin-footer.php'; ?>


    </div>

</main>
    <!-- Popper -->
    <script src="../assets/js/core/popper.min.js"></script>

    <!-- Bootstrap -->
    <script src="../assets/js/core/bootstrap.min.js"></script>

    <!-- Perfect Scrollbar -->
    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>

    <!-- Smooth Scrollbar -->
    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>

    <!-- Material Dashboard -->
    <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>

</html>
