<?php

require_once '../../core/Middleware.php';
require_once '../../config/database.php';
require_once '../../core/Session.php';

Middleware::admin();
Session::start();

$pageTitle = "Edit Product";


// =========================
// GET PRODUCT ID
// =========================

$productId = (int) ($_GET['id'] ?? 0);

if ($productId <= 0) {

    Session::setFlash(
        'error',
        'Invalid product ID.'
    );

    header('Location: index.php');
    exit;
}


// =========================
// FETCH PRODUCT
// =========================

$sql = "SELECT *
        FROM products
        WHERE id = :id
        LIMIT 1";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':id' => $productId
]);

$product = $stmt->fetch();


// =========================
// PRODUCT NOT FOUND
// =========================

if (!$product) {

    Session::setFlash(
        'error',
        'Product not found.'
    );

    header('Location: index.php');
    exit;
}

// =========================
// FETCH PRODUCT IMAGES
// =========================
$sql = "SELECT id, image
        FROM product_images
        WHERE product_id = :product_id
        ORDER BY id ASC";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    ':product_id' => $productId
]);

$productImages = $stmt->fetchAll();


// =========================
// FETCH ACTIVE CATEGORIES
// =========================

$sql = "SELECT id, name
        FROM categories
        WHERE status = 1
        ORDER BY name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$categories = $stmt->fetchAll();

// =========================
// DELETE PRIMARY IMAGE
// =========================

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['delete_primary_image'])
) {

    // Check if a primary image exists
    if (empty($product['image'])) {

        Session::setFlash(
            'error',
            'No primary image exists.'
        );

        header("Location: edit.php?id={$productId}");
        exit;
    }


    // Save current primary image filename
    $primaryImageToDelete = $product['image'];


    // =========================
    // REMOVE PRIMARY IMAGE FROM PRODUCTS
    // =========================

    $sql = "UPDATE products
            SET image = NULL
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $productId
    ]);

    // =========================
// REMOVE PRIMARY IMAGE FROM PRODUCT_IMAGES
// =========================

    $sql = "DELETE FROM product_images
        WHERE product_id = :product_id
        AND image = :image";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':product_id' => $productId,
        ':image' => $primaryImageToDelete
    ]);

    // =========================
    // DELETE PHYSICAL FILE
    // =========================

    $imagePath =
        '../../public/uploads/products/'
        . $primaryImageToDelete;

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }


    Session::setFlash(
        'success',
        'Primary image deleted successfully.'
    );

    header("Location: edit.php?id={$productId}");
    exit;
}

// =========================
// DELETE PRODUCT IMAGE
// =========================

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['delete_image'])
) {

    $imageId = (int) ($_POST['image_id'] ?? 0);

    if ($imageId <= 0) {

        Session::setFlash(
            'error',
            'Invalid image.'
        );

        header("Location: edit.php?id={$productId}");
        exit;
    }


    // =========================
    // GET IMAGE TO DELETE
    // =========================

    $sql = "SELECT id, image
            FROM product_images
            WHERE id = :id
            AND product_id = :product_id
            LIMIT 1";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $imageId,
        ':product_id' => $productId
    ]);

    $imageToDelete = $stmt->fetch();


    if (!$imageToDelete) {

        Session::setFlash(
            'error',
            'Image not found.'
        );

        header("Location: edit.php?id={$productId}");
        exit;
    }


    // =========================
    // DELETE DATABASE RECORD
    // =========================

    $sql = "DELETE FROM product_images
            WHERE id = :id
            AND product_id = :product_id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $imageId,
        ':product_id' => $productId
    ]);


    // =========================
    // DELETE PHYSICAL FILE
    // =========================

    $imagePath =
        '../../public/uploads/products/'
        . $imageToDelete['image'];

    if (file_exists($imagePath)) {
        unlink($imagePath);
    }


    Session::setFlash(
        'success',
        'Product image deleted successfully.'
    );

    header("Location: edit.php?id={$productId}");
    exit;
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['update_product'])
) {

    // =========================
    // GET FORM DATA
    // =========================

    $name = trim($_POST['product_name'] ?? '');

    $slug = trim($_POST['slug'] ?? '');

    $categoryId = (int) ($_POST['category'] ?? 0);

    $description = trim(
        $_POST['description'] ?? ''
    );

    $price = trim(
        $_POST['price'] ?? ''
    );

    $stock = (int) (
        $_POST['stock'] ?? 0
    );

    $status = $_POST['status'] ?? 'active';

    $primaryImage = $_FILES['primary_image'] ?? null;

    $images = $_FILES['images'] ?? null;
    // =========================
    // VALIDATION
    // =========================

    if ($name === '') {

        Session::setFlash(
            'error',
            'Product name is required.'
        );

        header("Location: edit.php?id={$productId}");
        exit;
    }


    if ($slug === '') {

        Session::setFlash(
            'error',
            'Product slug is required.'
        );

        header("Location: edit.php?id={$productId}");
        exit;
    }


    if ($categoryId <= 0) {

        Session::setFlash(
            'error',
            'Please select a category.'
        );

        header("Location: edit.php?id={$productId}");
        exit;
    }


    if (
        $price === ''
        || !is_numeric($price)
        || $price < 0
    ) {

        Session::setFlash(
            'error',
            'Please enter a valid product price.'
        );

        header("Location: edit.php?id={$productId}");
        exit;
    }


    if ($stock < 0) {

        Session::setFlash(
            'error',
            'Stock cannot be negative.'
        );

        header("Location: edit.php?id={$productId}");
        exit;
    }


    // =========================
    // CLEAN SLUG
    // =========================

    $slug = strtolower($slug);

    $slug = preg_replace(
        '/[^a-z0-9]+/i',
        '-',
        $slug
    );

    $slug = trim($slug, '-');


    // =========================
    // STATUS
    // =========================

    $statusValue =
        ($status === 'active') ? 1 : 0;


    // =========================
    // CHECK CATEGORY
    // =========================

    $sql = "SELECT id
            FROM categories
            WHERE id = :id
            LIMIT 1";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $categoryId
    ]);

    if (!$stmt->fetch()) {

        Session::setFlash(
            'error',
            'Selected category does not exist.'
        );

        header("Location: edit.php?id={$productId}");
        exit;
    }


    // =========================
    // CHECK DUPLICATE NAME/SLUG
    // EXCLUDING CURRENT PRODUCT
    // =========================

    $sql = "SELECT id
            FROM products
            WHERE (name = :name OR slug = :slug)
            AND id != :id
            LIMIT 1";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':name' => $name,
        ':slug' => $slug,
        ':id' => $productId
    ]);

    if ($stmt->fetch()) {

        Session::setFlash(
            'error',
            'Another product already uses this name or slug.'
        );

        header("Location: edit.php?id={$productId}");
        exit;
    }


    // =========================
    // UPDATE DATABASE
    // =========================

    $sql = "UPDATE products
            SET
                category_id = :category_id,
                name = :name,
                slug = :slug,
                description = :description,
                price = :price,
                stock = :stock,
                status = :status
            WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':category_id' => $categoryId,
        ':name' => $name,
        ':slug' => $slug,
        ':description' => $description,
        ':price' => $price,
        ':stock' => $stock,
        ':status' => $statusValue,
        ':id' => $productId
    ]);


    // =========================
// UPDATE PRIMARY IMAGE
// =========================

    if (
        $primaryImage
        && isset($primaryImage['name'])
        && $primaryImage['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        // =========================
        // CHECK UPLOAD ERROR
        // =========================

        if ($primaryImage['error'] !== UPLOAD_ERR_OK) {

            Session::setFlash(
                'error',
                'There was a problem uploading the primary image.'
            );

            header("Location: edit.php?id={$productId}");
            exit;
        }


        // =========================
        // CHECK SIZE
        // =========================

        if ($primaryImage['size'] > 2 * 1024 * 1024) {

            Session::setFlash(
                'error',
                'Primary image must be less than 2MB.'
            );

            header("Location: edit.php?id={$productId}");
            exit;
        }


        // =========================
        // TEMPORARY FILE
        // =========================

        $tmpName = $primaryImage['tmp_name'];


        // =========================
        // MIME TYPE
        // =========================

        $imageType = mime_content_type($tmpName);


        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];


        // =========================
        // VALIDATE TYPE
        // =========================

        if (!in_array($imageType, $allowedTypes)) {

            Session::setFlash(
                'error',
                'Only JPG, PNG, and WebP images are allowed for the primary image.'
            );

            header("Location: edit.php?id={$productId}");
            exit;
        }


        // =========================
        // IMAGE EXTENSION
        // =========================

        $extension = match ($imageType) {

            'image/jpeg' => 'jpg',

            'image/png' => 'png',

            'image/webp' => 'webp',

            default => null
        };


        // =========================
        // NEW IMAGE NAME
        // =========================

        $newPrimaryImage =
            uniqid('product_', true)
            . '.'
            . $extension;


        // =========================
        // UPLOAD DIRECTORY
        // =========================

        $uploadDirectory =
            '../../public/uploads/products/';


        $uploadPath =
            $uploadDirectory . $newPrimaryImage;


        // =========================
        // MOVE NEW IMAGE
        // =========================

        if (
            !move_uploaded_file(
                $tmpName,
                $uploadPath
            )
        ) {

            Session::setFlash(
                'error',
                'Failed to upload the primary image.'
            );

            header("Location: edit.php?id={$productId}");
            exit;
        }


        // =========================
        // SAVE OLD PRIMARY IMAGE
        // =========================

        $oldPrimaryImage = $product['image'];


        // =========================
        // UPDATE PRODUCTS TABLE
        // =========================

        $sql = "UPDATE products
            SET image = :image
            WHERE id = :id";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':image' => $newPrimaryImage,
            ':id' => $productId
        ]);

        // =========================
// REMOVE NEW PRIMARY FROM PRODUCT_IMAGES
// =========================

        $sql = "DELETE FROM product_images
        WHERE product_id = :product_id
        AND image = :image";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':product_id' => $productId,
            ':image' => $newPrimaryImage
        ]);

        // =========================
        // DELETE OLD PRIMARY FILE
        // =========================

        if (!empty($oldPrimaryImage)) {

            $oldImagePath =
                $uploadDirectory . $oldPrimaryImage;

            if (
                file_exists($oldImagePath)
                && $oldPrimaryImage !== $newPrimaryImage
            ) {
                unlink($oldImagePath);
            }
        }
    }

    // =========================
// UPLOAD NEW PRODUCT IMAGES
// =========================

    if (
        $images
        && isset($images['name'])
        && is_array($images['name'])
    ) {

        $uploadDirectory =
            '../../public/uploads/products/';

        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        foreach ($images['name'] as $key => $originalName) {

            // Skip empty file inputs
            if (
                empty($originalName)
                || $images['error'][$key] === UPLOAD_ERR_NO_FILE
            ) {
                continue;
            }


            // Check upload error
            if (
                $images['error'][$key] !== UPLOAD_ERR_OK
            ) {

                Session::setFlash(
                    'error',
                    'There was a problem uploading one of the images.'
                );

                header("Location: edit.php?id={$productId}");
                exit;
            }


            // Check size
            if (
                $images['size'][$key] > 2 * 1024 * 1024
            ) {

                Session::setFlash(
                    'error',
                    'Each image must be less than 2MB.'
                );

                header("Location: edit.php?id={$productId}");
                exit;
            }


            // Temporary file
            $tmpName = $images['tmp_name'][$key];


            // MIME type
            $imageType = mime_content_type($tmpName);


            // Validate type
            if (
                !in_array(
                    $imageType,
                    $allowedTypes
                )
            ) {

                Session::setFlash(
                    'error',
                    'Only JPG, PNG, and WebP images are allowed.'
                );

                header("Location: edit.php?id={$productId}");
                exit;
            }


            // =========================
            // IMAGE EXTENSION
            // =========================

            $extension = match ($imageType) {

                'image/jpeg' => 'jpg',

                'image/png' => 'png',

                'image/webp' => 'webp',

                default => null
            };


            // =========================
            // IMAGE NAME
            // =========================

            $imageName =
                uniqid(
                    'product_',
                    true
                ) . '.' . $extension;


            // =========================
            // UPLOAD PATH
            // =========================

            $uploadPath =
                $uploadDirectory . $imageName;


            // =========================
            // MOVE IMAGE
            // =========================

            if (
                !move_uploaded_file(
                    $tmpName,
                    $uploadPath
                )
            ) {

                Session::setFlash(
                    'error',
                    'Failed to upload one of the images.'
                );

                header("Location: edit.php?id={$productId}");
                exit;
            }


            // =========================
            // INSERT IMAGE
            // =========================

            $sql = "INSERT INTO product_images
                (
                    product_id,
                    image
                )
                VALUES
                (
                    :product_id,
                    :image
                )";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':product_id' => $productId,
                ':image' => $imageName
            ]);
        }
    }





    // =========================
    // SUCCESS
    // =========================

    Session::setFlash(
        'success',
        'Product updated successfully.'
    );

    header('Location: index.php');
    exit;
}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">

    <link rel="icon" type="image/png" href="../assets/img/favicon.png">

    <title>Edit Product - ElectroCart</title>


    <!-- Fonts -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900">


    <!-- Nucleo Icons -->

    <link href="../assets/css/nucleo-icons.css" rel="stylesheet">

    <link href="../assets/css/nucleo-svg.css" rel="stylesheet">


    <!-- Font Awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">


    <!-- Material Icons -->

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">


    <!-- Material Dashboard CSS -->

    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet">


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
   CURRENT PRODUCT IMAGES
========================== */

        .current-images-grid {

            display: grid;

            grid-template-columns:
                repeat(auto-fill, minmax(150px, 1fr));

            gap: 18px;

            margin-bottom: 20px;
        }


        /* =========================
   IMAGE CARD
========================== */

        .current-image-card {

            padding: 10px;

            border: 1px solid #e9ecef;

            border-radius: 12px;

            background: #ffffff;

            box-shadow:
                0 2px 8px rgba(0, 0, 0, 0.04);
        }


        /* =========================
   IMAGE
========================== */

        .current-image-card img {

            width: 100%;
            height: 150px;

            object-fit: contain;

            background: #f8f9fa;

            border-radius: 8px;

            display: block;

            margin-bottom: 10px;
        }


        /* =========================
   IMAGE ACTIONS
========================== */

        .current-image-actions {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 8px;
        }


        /* =========================
   IMAGE LABEL
========================== */

        .image-label {

            font-size: 11px;

            font-weight: 600;

            color: #8392ab;
        }

        /* =========================
   PRIMARY IMAGE CARD
========================== */

        .primary-image-card {
            border: 1px solid #e9ecef;
            border-radius: 14px;
            background: #ffffff;
            padding: 16px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }


        /* =========================
   PRIMARY IMAGE PREVIEW
========================== */

        .primary-image-preview {
            width: 100%;
            min-height: 320px;
            max-height: 420px;

            display: flex;
            align-items: center;
            justify-content: center;

            background: #f8f9fa;

            border: 1px solid #e9ecef;
            border-radius: 12px;

            padding: 20px;

            overflow: hidden;
        }


        /* =========================
   PRIMARY IMAGE
========================== */

        .primary-image-preview img {
            width: 100%;
            height: 100%;

            max-width: 100%;
            max-height: 380px;

            object-fit: contain;

            border-radius: 8px;

            display: block;
        }


        /* =========================
   PRIMARY IMAGE INFO
========================== */

        .primary-image-info {
            display: flex;

            align-items: center;
            justify-content: space-between;

            gap: 15px;

            padding-top: 15px;
        }


        /* =========================
   PRIMARY BADGE
========================== */

        .primary-badge {
            display: inline-flex;

            align-items: center;

            gap: 6px;

            padding: 6px 10px;

            border-radius: 6px;

            background: #fff8e1;

            color: #b78103;

            font-size: 12px;

            font-weight: 700;
        }


        /* =========================
   PRIMARY IMAGE NAME
========================== */

        .primary-image-name {
            margin: 8px 0 0;

            font-size: 12px;

            color: #8392ab;

            word-break: break-all;
        }


        /* =========================
   PRIMARY IMAGE MOBILE
========================== */

        @media (max-width: 576px) {

            .primary-image-preview {
                min-height: 240px;
                max-height: 300px;

                padding: 15px;
            }

            .primary-image-preview img {
                max-height: 270px;
            }

            .primary-image-info {
                flex-direction: column;

                align-items: stretch;
            }

            .primary-image-info .btn {
                width: 100%;
            }

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

                <a href="index.php" class="btn btn-outline-secondary btn-sm">

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

                            <form action="" method="POST" enctype="multipart/form-data">

                                <!-- =========================
                                PRODUCT NAME
                                ========================== -->

                                <div class="product-field">

                                    <label for="product_name">

                                        <i class="fa-solid fa-box"></i>

                                        Product Name

                                    </label>


                                    <input type="text" id="product_name" name="product_name" class="product-input"
                                        value="<?= htmlspecialchars($product['name']) ?>"
                                        placeholder="Enter product name" required>


                                    <span class="field-help">
                                        Enter the name of the product.
                                    </span>

                                </div>


                                <!-- =========================
                                    PRODUCT SLUG
                                ========================== -->

                                <div class="product-field">

                                    <label for="slug">

                                        <i class="fa-solid fa-link"></i>

                                        Product Slug

                                    </label>


                                    <input type="text" id="slug" name="slug" class="product-input"
                                        value="<?= htmlspecialchars($product['slug']) ?>"
                                        placeholder="e.g. classic-running-shoes" required>


                                    <span class="field-help">
                                        The slug is used in product URLs.
                                        Use lowercase letters, numbers, and hyphens.
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


                                            <select id="category" name="category" class="product-input" required>

                                                <option value="">
                                                    Select Category
                                                </option>


                                                <?php foreach ($categories as $category): ?>

                                                    <option value="<?= (int) $category['id'] ?>" <?= (
                                                           (int) $product['category_id']
                                                           === (int) $category['id']
                                                       ) ? 'selected' : '' ?>>

                                                        <?= htmlspecialchars($category['name']) ?>

                                                    </option>

                                                <?php endforeach; ?>

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


                                            <input type="number" id="price" name="price" class="product-input"
                                                value="<?= htmlspecialchars($product['price']) ?>"
                                                placeholder="Enter price" min="0" step="0.01" required>


                                            <span class="field-help">
                                                Enter the product price.
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


                                            <input type="number" id="stock" name="stock" class="product-input"
                                                value="<?= (int) $product['stock'] ?>"
                                                placeholder="Enter stock quantity" min="0" required>


                                            <span class="field-help">
                                                Enter the available quantity.
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


                                            <select id="status" name="status" class="product-input" required>

                                                <option value="active" <?= (
                                                    (int) $product['status'] === 1
                                                ) ? 'selected' : '' ?>>
                                                    Active
                                                </option>


                                                <option value="inactive" <?= (
                                                    (int) $product['status'] === 0
                                                ) ? 'selected' : '' ?>>
                                                    Inactive
                                                </option>

                                            </select>


                                            <span class="field-help">
                                                Choose whether this product is active.
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


                                    <textarea id="description" name="description" class="product-input" rows="5"
                                        placeholder="Enter product description"><?= htmlspecialchars($product['description'] ?? '') ?></textarea>


                                    <span class="field-help">
                                        Add a short description of the product.
                                    </span>

                                </div>



                                <!-- =========================
                                    PRIMARY IMAGE
                                ========================== -->

                                <div class="product-field">

                                    <label>

                                        <i class="fa-solid fa-star"></i>

                                        Primary Image

                                    </label>


                                    <!-- CURRENT PRIMARY IMAGE -->

                                    <?php if (!empty($product['image'])): ?>

                                        <div class="primary-image-card">

                                            <div class="primary-image-preview">

                                                <img src="../../public/uploads/products/<?= htmlspecialchars($product['image']) ?>"
                                                    alt="<?= htmlspecialchars($product['name']) ?>">

                                            </div>

                                            <div class="primary-image-info">

                                                <div>

                                                    <span class="primary-badge">
                                                        <i class="fa-solid fa-star"></i>
                                                        Primary Image
                                                    </span>

                                                    <p class="primary-image-name">
                                                        <?= htmlspecialchars($product['image']) ?>
                                                    </p>

                                                </div>

                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    data-bs-toggle="modal" data-bs-target="#deletePrimaryImageModal">

                                                    <i class="fa-solid fa-trash me-1"></i>
                                                    Delete Primary Image

                                                </button>

                                            </div>

                                        </div>

                                    <?php else: ?>

                                        <div class="alert alert-light border text-sm mb-3">

                                            <i class="fa-solid fa-image me-1"></i>

                                            No primary image has been set.

                                        </div>

                                    <?php endif; ?>


                                    <!-- NEW PRIMARY IMAGE -->

                                    <input type="file" id="primary_image" name="primary_image" class="product-input"
                                        accept="image/jpeg,image/png,image/webp">

                                    <span class="field-help">

                                        Upload a new image to replace the current primary image.
                                        JPG, PNG or WEBP only. Maximum size: 2MB.

                                    </span>

                                </div>


                                <!-- =========================
                                        OTHER PRODUCT IMAGES
                                ========================== -->

                                <div class="product-field">

                                    <label>

                                        <i class="fa-solid fa-images"></i>

                                        Other Product Images

                                    </label>


                                    <!-- CURRENT OTHER IMAGES -->

                                    <?php if (!empty($productImages)): ?>

                                        <div class="current-images-grid">

                                            <?php foreach ($productImages as $productImage): ?>

                                                <div class="current-image-card">

                                                    <img src="../../public/uploads/products/<?= htmlspecialchars($productImage['image']) ?>"
                                                        alt="<?= htmlspecialchars($product['name']) ?>">


                                                    <div class="current-image-actions">

                                                        <span class="image-label">

                                                            <i class="fa-solid fa-image me-1"></i>

                                                            Additional Image

                                                        </span>


                                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                                            onclick="openDeleteProductImageModal(<?= (int) $productImage['id'] ?>);">

                                                            <i class="fa-solid fa-trash"></i>
                                                            Delete

                                                        </button>

                                                    </div>

                                                </div>

                                            <?php endforeach; ?>

                                        </div>

                                    <?php else: ?>

                                        <div class="alert alert-light border text-sm">

                                            No additional product images found.

                                        </div>

                                    <?php endif; ?>


                                    <!-- ADD OTHER IMAGES -->

                                    <input type="file" id="images" name="images[]" class="product-input"
                                        accept="image/jpeg,image/png,image/webp" multiple>

                                    <span class="field-help">

                                        Add one or multiple additional product images.
                                        These images are separate from the primary image.
                                        JPG, PNG or WEBP only. Maximum size: 2MB per image.

                                    </span>

                                </div>


                                <!-- =========================
                                    BUTTONS
                                ========================== -->

                                <div class="product-form-actions">


                                    <!-- CANCEL -->

                                    <a href="index.php" class="btn btn-light cancel-btn">

                                        <i class="fa-solid fa-xmark me-1"></i>

                                        Cancel

                                    </a>


                                    <!-- UPDATE -->

                                    <button type="submit" name="update_product" class="btn bg-gradient-dark create-btn">

                                        <i class="fa-solid fa-check me-1"></i>

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
    <script>

    function openDeleteProductImageModal(imageId) {

        document.getElementById('deleteProductImageId').value = imageId;

        const modalElement = document.getElementById('deleteProductImageModal');

        const modal = new bootstrap.Modal(modalElement);

        modal.show();
    }

</script>
    <!-- Delete Primary Image Modal -->
    <div class="modal fade" id="deletePrimaryImageModal" tabindex="-1" aria-labelledby="deletePrimaryImageModalLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="modal-title" id="deletePrimaryImageModalLabel">
                        <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>
                        Delete Primary Image
                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="text-center py-2">

                        <div class="mb-3">
                            <i class="fa-solid fa-image-slash text-danger" style="font-size: 45px;">
                            </i>
                        </div>

                        <h6 class="mb-2">
                            Are you sure?
                        </h6>

                        <p class="text-sm text-secondary mb-0">
                            This will permanently delete the primary image
                            from this product.
                        </p>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">

                        Cancel

                    </button>


                    <form action="edit.php?id=<?= $productId ?>" method="POST">

                        <input type="hidden" name="delete_primary_image" value="1">

                        <button type="submit" class="btn btn-danger">

                            <i class="fa-solid fa-trash me-1"></i>

                            Yes, Delete Image

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>
    <!-- Delete Other Product Image Modal -->
<div class="modal fade"
    id="deleteProductImageModal"
    tabindex="-1"
    aria-labelledby="deleteProductImageModalLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="deleteProductImageModalLabel">

                    <i class="fa-solid fa-triangle-exclamation text-danger me-2"></i>

                    Delete Product Image

                </h5>

                <button type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                </button>

            </div>


            <div class="modal-body">

                <div class="text-center py-2">

                    <div class="mb-3">

                        <i class="fa-solid fa-image-slash text-danger"
                            style="font-size: 45px;">
                        </i>

                    </div>

                    <h6 class="mb-2">
                        Are you sure?
                    </h6>

                    <p class="text-sm text-secondary mb-0">

                        This will permanently delete this additional
                        product image.

                    </p>

                </div>

            </div>


            <div class="modal-footer">

                <button type="button"
                    class="btn btn-light"
                    data-bs-dismiss="modal">

                    Cancel

                </button>


                <form action="edit.php?id=<?= $productId ?>" method="POST">

                    <input type="hidden"
                        name="delete_image"
                        value="1">

                    <input type="hidden"
                        name="image_id"
                        id="deleteProductImageId"
                        value="">

                    <button type="submit"
                        class="btn btn-danger">

                        <i class="fa-solid fa-trash me-1"></i>

                        Yes, Delete Image

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>
    <script src="assets/js/core/bootstrap.bundle.min.js"></script>
</body>

</html>