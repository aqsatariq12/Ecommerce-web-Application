<?php

require_once '../../core/Middleware.php';
require_once '../../config/database.php';
require_once '../../core/Session.php';
require_once '../../core/Deal.php';

Middleware::admin();
Session::start();

$pageTitle = "Edit Deal";


// =========================
// FLASH MESSAGES
// =========================

$successMessage = Session::getFlash('success');
$errorMessage = Session::getFlash('error');


// =========================
// GET DEAL ID
// =========================

$dealId = (int) ($_GET['id'] ?? 0);

if ($dealId <= 0) {

    Session::setFlash(
        'error',
        'Invalid deal ID.'
    );

    header('Location: index.php');
    exit;
}


// =========================
// FETCH DEAL
// =========================

$deal = Deal::getDealById($dealId);

if (!$deal) {

    Session::setFlash(
        'error',
        'Deal not found.'
    );

    header('Location: index.php');
    exit;
}


// =========================
// FETCH PRODUCTS
// =========================

$productSql = "SELECT
                    id,
                    name,
                    price,
                    image
               FROM products
               WHERE status = 1
               ORDER BY name ASC";

$productStmt = $pdo->prepare($productSql);
$productStmt->execute();

$products = $productStmt->fetchAll();


// =========================
// FORM VALUES
// =========================

$productId = (int) $deal['product_id'];

$title = $deal['title'];

$subtitle = $deal['subtitle'] ?? '';

$oldPrice = $deal['old_price'] ?? '';

$countdownUntil = $deal['countdown_until'] ?? '';

$buttonText = $deal['button_text'] ?? 'Shop Now';

$buttonLink = $deal['button_link'] ?? 'product-detail.php';

$status = (int) $deal['status'];

$currentImage = $deal['background_image'];


// =========================
// UPDATE DEAL
// =========================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $productId = (int) ($_POST['product_id'] ?? 0);

    $title = trim($_POST['title'] ?? '');

    $subtitle = trim($_POST['subtitle'] ?? '');

    $oldPrice = trim($_POST['old_price'] ?? '');

    $countdownUntil = trim($_POST['countdown_until'] ?? '');

    $buttonText = trim($_POST['button_text'] ?? '');

    $buttonLink = trim($_POST['button_link'] ?? '');

    $statusValue = $_POST['status'] ?? 'active';

    $status = ($statusValue === 'active') ? 1 : 0;


    // =========================
    // VALIDATION
    // =========================

    if ($productId <= 0) {

        $errorMessage = "Please select a product.";

    } elseif ($title === '') {

        $errorMessage = "Deal title is required.";

    } elseif (strlen($title) > 200) {

        $errorMessage = "Deal title cannot exceed 200 characters.";

    } elseif (
        $subtitle !== ''
        && strlen($subtitle) > 255
    ) {

        $errorMessage = "Subtitle cannot exceed 255 characters.";

    } elseif (
        $oldPrice !== ''
        && (!is_numeric($oldPrice) || $oldPrice < 0)
    ) {

        $errorMessage = "Please enter a valid old price.";

    } elseif ($buttonText === '') {

        $errorMessage = "Button text is required.";

    } elseif (strlen($buttonText) > 100) {

        $errorMessage = "Button text cannot exceed 100 characters.";

    } elseif ($buttonLink === '') {

        $errorMessage = "Button link is required.";

    }


    // =========================
    // IMAGE VARIABLES
    // =========================

    $newImageName = $currentImage;

    $newImagePath = null;

    $oldImagePath =
        '../../public/uploads/deals/' . $currentImage;


    // =========================
    // NEW IMAGE UPLOAD
    // =========================

    if (
        !$errorMessage
        && isset($_FILES['background_image'])
        && $_FILES['background_image']['error'] !== UPLOAD_ERR_NO_FILE
    ) {

        $image = $_FILES['background_image'];


        // Get actual MIME type

        $imageType = mime_content_type(
            $image['tmp_name']
        );


        $allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];


        if ($image['error'] !== UPLOAD_ERR_OK) {

            $errorMessage =
                "There was a problem uploading the image.";

        } elseif (
            !in_array($imageType, $allowedTypes, true)
        ) {

            $errorMessage =
                "Only JPG, PNG and WebP images are allowed.";

        } elseif ($image['size'] > 5 * 1024 * 1024) {

            $errorMessage =
                "Image size must not exceed 5MB.";

        } else {

            // =========================
            // UPLOAD DIRECTORY
            // =========================

            $uploadDirectory =
                '../../public/uploads/deals/';


            if (!is_dir($uploadDirectory)) {

                mkdir(
                    $uploadDirectory,
                    0777,
                    true
                );

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

            $newImageName =
                'deal_' .
                time() .
                '_' .
                uniqid() .
                '.' .
                $extension;


            $newImagePath =
                $uploadDirectory . $newImageName;


            // =========================
            // MOVE IMAGE
            // =========================

            if (
                !move_uploaded_file(
                    $image['tmp_name'],
                    $newImagePath
                )
            ) {

                $errorMessage =
                    "Failed to upload the new deal image.";

            }

        }

    }


    // =========================
    // UPDATE DATABASE
    // =========================

    if (!$errorMessage) {

        $data = [

            'product_id' => $productId,

            'title' => $title,

            'subtitle' =>
                $subtitle !== ''
                ? $subtitle
                : null,

            'old_price' =>
                $oldPrice !== ''
                ? $oldPrice
                : null,

            'countdown_until' =>
                $countdownUntil !== ''
                ? $countdownUntil
                : null,

            'background_image' =>
                $newImageName,

            'button_text' =>
                $buttonText,

            'button_link' =>
                $buttonLink,

            'status' =>
                $status

        ];


        try {

            Deal::update(
                $dealId,
                $data
            );


            // =========================
            // DELETE OLD IMAGE
            // =========================

            if (
                $newImageName !== $currentImage
                && !empty($currentImage)
                && file_exists($oldImagePath)
            ) {

                unlink($oldImagePath);

            }


            // =========================
            // SUCCESS
            // =========================

            Session::setFlash(
                'success',
                'Deal updated successfully.'
            );

            header('Location: index.php');

            exit;


        } catch (PDOException $e) {

            // Remove new image if database update fails

            if (
                $newImagePath
                && file_exists($newImagePath)
            ) {

                unlink($newImagePath);

            }


            $newImageName = $currentImage;

            $errorMessage =
                'Failed to update deal. Please try again.';

        }

    }

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">

    <link rel="icon" type="image/png" href="../assets/img/favicon.png">

    <title>Edit Deal - ClothWear</title>


    <!-- Fonts -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900">


    <!-- Nucleo Icons -->

    <link href="../assets/css/nucleo-icons.css" rel="stylesheet">

    <link href="../assets/css/nucleo-svg.css" rel="stylesheet">


    <!-- Font Awesome -->

    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous">
    </script>


    <!-- Material Icons -->

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0">


    <!-- Material Dashboard -->

    <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet">


    <!-- =========================
         DEAL FORM CSS
    ========================== -->

    <style>
        /* =========================
           FORM CARD
        ========================== */

        .deal-form-card {

            border-radius: 16px;

            overflow: hidden;

        }


        /* =========================
           CARD HEADER
        ========================== */

        .deal-form-header {

            padding: 28px 30px 18px;

            border-bottom: 1px solid #e9ecef;

        }


        .deal-form-header h5 {

            font-weight: 700;

            color: #212529;

        }


        .deal-form-header p {

            margin-bottom: 0;

            color: #67748e;

        }


        /* =========================
           FORM BODY
        ========================== */

        .deal-form-body {

            padding: 30px;

        }


        /* =========================
           FORM FIELD
        ========================== */

        .deal-field {

            margin-bottom: 28px;

        }


        /* =========================
           LABEL
        ========================== */

        .deal-field label {

            display: flex;

            align-items: center;

            gap: 8px;

            margin-bottom: 10px;

            font-size: 14px;

            font-weight: 700;

            color: #344767;

        }


        /* Label Icon */

        .deal-field label i {

            font-size: 14px;

            color: #2e37a4;

        }


        /* =========================
           INPUT + SELECT
        ========================== */

        .deal-input {

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


        /* =========================
           PLACEHOLDER
        ========================== */

        .deal-input::placeholder {

            color: #adb5bd;

        }


        /* =========================
           INPUT FOCUS
        ========================== */

        .deal-input:focus {

            border-color: #2e37a4;

            box-shadow:
                0 0 0 3px rgba(46, 55, 164, 0.10);

            outline: none;

        }


        /* =========================
           SELECT
        ========================== */

        select.deal-input {

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
           CURRENT IMAGE
        ========================== */

        .current-deal-image {

            width: 100%;

            max-width: 500px;

            height: 220px;

            object-fit: cover;

            border-radius: 10px;

            display: block;

            margin-top: 12px;

            border: 1px solid #e9ecef;

        }


        /* =========================
           NEW IMAGE PREVIEW
        ========================== */

        .deal-image-preview {

            width: 100%;

            max-width: 500px;

            height: 220px;

            object-fit: cover;

            border-radius: 10px;

            display: none;

            margin-top: 12px;

            border: 1px solid #e9ecef;

        }


        /* =========================
           FILE INPUT
        ========================== */

        .deal-input[type="file"] {

            padding: 10px 12px;

            cursor: pointer;

        }


        /* =========================
           BUTTON AREA
        ========================== */

        .deal-form-actions {

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

        .deal-form-actions .update-btn {

            min-width: 160px;

            padding: 11px 18px;

            font-weight: 600;

        }


        /* =========================
           CANCEL BUTTON
        ========================== */

        .deal-form-actions .cancel-btn {

            min-width: 100px;

            padding: 11px 18px;

            font-weight: 600;

        }


        /* =========================
           MOBILE RESPONSIVE
        ========================== */

        @media (max-width: 576px) {

            .deal-form-body {

                padding: 20px;

            }


            .deal-form-header {

                padding: 22px 20px 16px;

            }


            .deal-form-actions {

                flex-direction: column-reverse;

            }


            .deal-form-actions .btn {

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

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">


                <div>

                    <h4 class="fw-bold text-dark mb-1">

                        Edit Deal

                    </h4>

                    <p class="text-sm text-secondary mb-0">

                        Update the details of this promotional deal

                    </p>

                </div>


                <!-- BACK BUTTON -->

                <a href="index.php" class="btn btn-outline-secondary btn-sm">

                    <i class="fa-solid fa-arrow-left me-1"></i>

                    Back to Deals

                </a>

            </div>


            <!-- =========================
                 FORM
            ========================== -->

            <div class="row">

                <div class="col-lg-8 col-md-10 mx-auto">


                    <!-- FORM CARD -->

                    <div class="card deal-form-card">


                        <!-- =========================
                             CARD HEADER
                        ========================== -->

                        <div class="deal-form-header">

                            <h5 class="mb-1">

                                Deal Information

                            </h5>

                            <p class="text-sm">

                                Update the details for this promotional deal.

                            </p>

                        </div>


                        <!-- =========================
                             FORM BODY
                        ========================== -->

                        <div class="deal-form-body">


                            <!-- =========================
                                 ERROR MESSAGE
                            ========================== -->

                            <?php if ($errorMessage): ?>

                                <div class="alert alert-danger alert-dismissible fade show" role="alert">

                                    <i class="fa-solid fa-circle-exclamation me-2">
                                    </i>

                                    <?= htmlspecialchars($errorMessage) ?>

                                    <button type="button" class="btn-close" data-bs-dismiss="alert">
                                    </button>

                                </div>

                            <?php endif; ?>


                            <!-- =========================
                                 FORM
                            ========================== -->

                            <form method="POST" action="" enctype="multipart/form-data">


                                <!-- =========================
                                     PRODUCT
                                ========================== -->

                                <div class="deal-field">

                                    <label for="productId">

                                        <i class="fa-solid fa-box"></i>

                                        Product

                                    </label>


                                    <select id="productId" name="product_id" class="deal-input" required>


                                        <option value="">

                                            Select Product

                                        </option>


                                        <?php foreach ($products as $product): ?>

                                            <option value="<?= (int) $product['id'] ?>" <?= $productId === (int) $product['id']
                                                   ? 'selected'
                                                   : '' ?>>

                                                <?= htmlspecialchars(
                                                    $product['name']
                                                ) ?>

                                                -
                                                $<?= number_format(
                                                    (float) $product['price'],
                                                    2
                                                ) ?>

                                            </option>

                                        <?php endforeach; ?>


                                    </select>


                                    <span class="field-help">

                                        Select the product that this deal belongs to.

                                    </span>

                                </div>


                                <!-- =========================
                                     DEAL TITLE
                                ========================== -->

                                <div class="deal-field">

                                    <label for="dealTitle">

                                        <i class="fa-solid fa-tag"></i>

                                        Deal Title

                                    </label>


                                    <input type="text" id="dealTitle" name="title" class="deal-input" maxlength="200"
                                        value="<?= htmlspecialchars($title) ?>" placeholder="e.g. Summer Sale" required>


                                    <span class="field-help">

                                        Enter a short and attractive title for the deal.

                                    </span>

                                </div>


                                <!-- =========================
                                     SUBTITLE
                                ========================== -->

                                <div class="deal-field">

                                    <label for="dealSubtitle">

                                        <i class="fa-solid fa-align-left"></i>

                                        Deal Subtitle

                                    </label>


                                    <input type="text" id="dealSubtitle" name="subtitle" class="deal-input"
                                        maxlength="255" value="<?= htmlspecialchars($subtitle) ?>"
                                        placeholder="e.g. Get amazing discounts today">


                                    <span class="field-help">

                                        Optional supporting text displayed below the deal title.

                                    </span>

                                </div>


                                <!-- =========================
                                     OLD PRICE
                                ========================== -->

                                <div class="deal-field">

                                    <label for="oldPrice">

                                        <i class="fa-solid fa-dollar-sign"></i>

                                        Old Price

                                    </label>


                                    <input type="number" id="oldPrice" name="old_price" class="deal-input" min="0"
                                        step="0.01" value="<?= htmlspecialchars($oldPrice) ?>" placeholder="e.g. 99.99">


                                    <span class="field-help">

                                        Optional original price. It will appear as the crossed-out price.

                                    </span>

                                </div>


                                <!-- =========================
                                     COUNTDOWN
                                ========================== -->

                                <div class="deal-field">

                                    <label for="countdownUntil">

                                        <i class="fa-solid fa-clock"></i>

                                        Countdown Until

                                    </label>


                                    <input type="datetime-local" id="countdownUntil" name="countdown_until"
                                        class="deal-input" value="<?= htmlspecialchars($countdownUntil) ?>">


                                    <span class="field-help">

                                        Optional ending date and time for the deal countdown.

                                    </span>

                                </div>


                                <!-- =========================
                                     CURRENT IMAGE
                                ========================== -->

                                <div class="deal-field">

                                    <label>

                                        <i class="fa-solid fa-image"></i>

                                        Current Deal Image

                                    </label>


                                    <?php if (!empty($currentImage)): ?>

                                        <img src="../../public/uploads/deals/<?= htmlspecialchars($currentImage) ?>"
                                            alt="<?= htmlspecialchars($title) ?>" class="current-deal-image">

                                    <?php else: ?>

                                        <div class="d-flex align-items-center justify-content-center bg-gray-100" style="
                                                width:100%;
                                                max-width:500px;
                                                height:220px;
                                                border-radius:10px;
                                                margin-top:12px;
                                            ">

                                            <i class="fa-solid fa-image text-secondary fs-3">
                                            </i>

                                        </div>

                                    <?php endif; ?>

                                </div>


                                <!-- =========================
                                     REPLACE IMAGE
                                ========================== -->

                                <div class="deal-field">

                                    <label for="backgroundImage">

                                        <i class="fa-solid fa-camera"></i>

                                        Replace Deal Image

                                    </label>


                                    <input type="file" id="backgroundImage" name="background_image" class="deal-input"
                                        accept="image/jpeg,image/png,image/webp">


                                    <span class="field-help">

                                        Leave empty to keep the current image.
                                        JPG, PNG, or WebP. Maximum size: 5MB.

                                    </span>


                                    <img id="imagePreview" class="deal-image-preview" alt="New deal image preview">

                                </div>


                                <!-- =========================
                                     BUTTON TEXT
                                ========================== -->

                                <div class="deal-field">

                                    <label for="buttonText">

                                        <i class="fa-solid fa-arrow-pointer"></i>

                                        Button Text

                                    </label>


                                    <input type="text" id="buttonText" name="button_text" class="deal-input"
                                        maxlength="100" value="<?= htmlspecialchars($buttonText) ?>"
                                        placeholder="e.g. Shop Now" required>


                                    <span class="field-help">

                                        Text that will appear on the deal button.

                                    </span>

                                </div>


                                <!-- =========================
                                     BUTTON LINK
                                ========================== -->

                                <div class="deal-field">

                                    <label for="buttonLink">

                                        <i class="fa-solid fa-link"></i>

                                        Button Link

                                    </label>


                                    <input type="text" id="buttonLink" name="button_link" class="deal-input"
                                        maxlength="255" value="<?= htmlspecialchars($buttonLink) ?>"
                                        placeholder="e.g. product-detail.php?id=1" required>


                                    <span class="field-help">

                                        Enter the page or URL that opens when the button is clicked.

                                    </span>

                                </div>


                                <!-- =========================
                                     STATUS
                                ========================== -->

                                <div class="deal-field">

                                    <label for="dealStatus">

                                        <i class="fa-solid fa-circle-check"></i>

                                        Deal Status

                                    </label>


                                    <select id="dealStatus" name="status" class="deal-input" required>


                                        <option value="active" <?= $status === 1
                                            ? 'selected'
                                            : '' ?>>

                                            Active

                                        </option>


                                        <option value="inactive" <?= $status === 0
                                            ? 'selected'
                                            : '' ?>>

                                            Inactive

                                        </option>


                                    </select>


                                    <span class="field-help">

                                        Inactive deals will not appear on the storefront.

                                    </span>

                                </div>


                                <!-- =========================
                                     BUTTONS
                                ========================== -->

                                <div class="deal-form-actions">


                                    <!-- CANCEL -->

                                    <a href="index.php" class="btn btn-light cancel-btn">

                                        <i class="fa-solid fa-xmark me-1">
                                        </i>

                                        Cancel

                                    </a>


                                    <!-- UPDATE -->

                                    <button type="submit" class="btn bg-gradient-dark update-btn">

                                        <i class="fa-solid fa-floppy-disk me-1">
                                        </i>

                                        Update Deal

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


    <!-- =========================
         IMAGE PREVIEW
    ========================== -->

    <script>

        const backgroundImage =
            document.getElementById('backgroundImage');

        const imagePreview =
            document.getElementById('imagePreview');


        backgroundImage.addEventListener(
            'change',
            function () {

                const file = this.files[0];


                if (file) {

                    const reader =
                        new FileReader();


                    reader.onload =
                        function (event) {

                            imagePreview.src =
                                event.target.result;

                            imagePreview.style.display =
                                'block';

                        };


                    reader.readAsDataURL(file);

                } else {

                    imagePreview.style.display =
                        'none';

                }

            }
        );

    </script>


    <!-- =========================
         JAVASCRIPT
    ========================== -->

    <script src="../assets/js/core/popper.min.js"></script>

    <script src="../assets/js/core/bootstrap.min.js"></script>

    <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>

    <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>

    <script src="../assets/js/material-dashboard.min.js?v=3.2.0"></script>

</body>

</html>