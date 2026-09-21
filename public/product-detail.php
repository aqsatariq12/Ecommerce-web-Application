<?php
require_once '../core/Middleware.php';
Middleware::customer();

require_once '../core/Product.php';
$productId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$productId) {
    header("Location: index.php");
    exit;
}

$product = Product::getById($productId);
if (!$product) {
    header("Location: index.php");
    exit;
}

$youMayAlsoLike = Product::getOneProductPerCategory($productId);


include '../includes/header.php';
?>
<!DOCTYPE html>
<html lang="en">


<!-- molla/product-gallery.html  22 Nov 2019 10:03:27 GMT -->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?= htmlspecialchars($product['name']) ?> - ClothWear
    </title>
    <meta name="keywords" content="HTML5 Template">
    <meta name="description" content="Molla - Bootstrap eCommerce Template">
    <meta name="author" content="p-themes">
    <!-- Favicon -->
    <link rel="apple-touch-icon" sizes="180x180" href="assets/images/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/images/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/icons/favicon-16x16.png">
    <link rel="manifest" href="assets/images/icons/site.html">
    <link rel="mask-icon" href="assets/images/icons/safari-pinned-tab.svg" color="#666666">
    <link rel="shortcut icon" href="assets/images/icons/favicon.ico">
    <meta name="apple-mobile-web-app-title" content="Molla">
    <meta name="application-name" content="Molla">
    <meta name="msapplication-TileColor" content="#cc9966">
    <meta name="msapplication-config" content="assets/images/icons/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">
    <!-- Plugins CSS File -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/plugins/owl-carousel/owl.carousel.css">
    <link rel="stylesheet" href="assets/css/plugins/magnific-popup/magnific-popup.css">
    <!-- Main CSS File -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/plugins/nouislider/nouislider.css">
</head>

<body>
    <div class="page-wrapper">

        <main class="main">
            <div class="page-content">
                <div class="product-details-top">
                    <div class="bg-light pb-5 mb-4">
                        <nav aria-label="breadcrumb" class="breadcrumb-nav border-0 mb-0">
                            <div class="container d-flex align-items-center">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                                    <li class="breadcrumb-item">
                                        <a href="products.php?category=<?= $product['category_id'] ?>">
                                            <?= htmlspecialchars($product['category_name']) ?>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        <?= htmlspecialchars($product['name']) ?>
                                    </li>
                                </ol>

                                <!-- End .pager-nav -->
                            </div><!-- End .container -->
                        </nav><!-- End .breadcrumb-nav -->
                        <div class="container">

                            <div class="row align-items-center bg-light">

                                <div class="col-lg-6 mb-4 mb-lg-0">

                                    <div class="text-center">

                                        <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>"
                                            alt="<?= htmlspecialchars($product['name']) ?>" class="img-fluid"
                                            style="max-height: 400px; width: 100%; object-fit: contain;">

                                    </div>

                                </div>


                                <div class="col-lg-6 d-flex justify-content-center">

                                    <div class="product-showcase-content w-100" style="max-width: 550px;">

                                        <!-- Category -->
                                        <div class="mb-2">

                                            <span class="text-muted text-uppercase"
                                                style="font-size: 13px; letter-spacing: 1px;">

                                                <?= htmlspecialchars($product['category_name']) ?>

                                            </span>

                                        </div>


                                        <!-- Product Name -->
                                        <h1 class="product-title mb-3" style="font-size: 32px; line-height: 1.2;">

                                            <?= htmlspecialchars($product['name']) ?>

                                        </h1>


                                        <!-- Price -->
                                        <div class="product-price mb-3" style="font-size: 28px; font-weight: 600;">

                                            $<?= number_format($product['price'], 2) ?>

                                        </div>


                                        <!-- Divider -->
                                        <div class="mb-3">

                                            <span style="
                display: block;
                width: 50px;
                height: 3px;
                background: #c96;
            "></span>

                                        </div>


                                        <!-- Description -->
                                        <div class="product-content mb-3">

                                            <p style="
                font-size: 16px;
                line-height: 1.7;
                margin-bottom: 0;
            ">

                                                <?= nl2br(htmlspecialchars($product['description'])) ?>

                                            </p>

                                        </div>


                                        <!-- Stock -->
                                        <div class="mb-4">

                                            <?php if ($product['stock'] > 0): ?>

                                                <span class="text-success" style="font-size: 15px; font-weight: 500;">

                                                    <i class="icon-check"></i>

                                                    In Stock
                                                    (<?= (int) $product['stock'] ?> available)

                                                </span>

                                            <?php else: ?>

                                                <span class="text-danger" style="font-size: 15px; font-weight: 500;">

                                                    Out of Stock

                                                </span>

                                            <?php endif; ?>

                                        </div>


                                        <!-- Product Information -->
                                        <div class="border-top pt-3">

                                            <div class="row">

                                                <div class="col-6 mb-2">

                                                    <strong>Category:</strong>

                                                    <span class="text-muted">
                                                        <?= htmlspecialchars($product['category_name']) ?>
                                                    </span>

                                                </div>


                                                <div class="col-6 mb-2">

                                                    <strong>Availability:</strong>

                                                    <?php if ($product['stock'] > 0): ?>

                                                        <span class="text-success">
                                                            Available
                                                        </span>

                                                    <?php else: ?>

                                                        <span class="text-danger">
                                                            Unavailable
                                                        </span>

                                                    <?php endif; ?>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                    </div><!-- End .bg-light pb-5 -->

                    <div class="product-details product-details-centered product-details-separator">

                        <div class="container">

                            <div class="row justify-content-center ">

                                <div class="col-md-8 col-lg-6 ">

                                    <!-- Cart Actions -->
                                    <div class="product-details-action">

                                        <form action="add-to-cart.php" method="POST">

                                            <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">

                                            <div class="details-action-col">

                                                <div class="product-details-quantity">

                                                    <input type="number" name="quantity" id="qty" class="form-control"
                                                        value="1" min="1" max="<?= (int) $product['stock'] ?>" step="1"
                                                        data-decimals="0" required>

                                                </div>


                                                <?php if ($product['stock'] > 0): ?>

                                                    <button type="submit" class="btn-product btn-cart">
                                                        <span>add to cart</span>
                                                    </button>

                                                <?php else: ?>

                                                    <button type="button" class="btn-product btn-cart" disabled>
                                                        <span>out of stock</span>
                                                    </button>

                                                <?php endif; ?>

                                            </div>

                                        </form>


                                        <div class="details-action-wrapper">

                                            <a href="#" class="btn-product btn-wishlist" title="Wishlist">
                                                <span>Add to Wishlist</span>
                                            </a>

                                            <a href="#" class="btn-product btn-compare" title="Compare">
                                                <span>Add to Compare</span>
                                            </a>

                                        </div>

                                    </div>



                                    <!-- Category + Share -->
                                    <div class="product-details-footer details-footer-col justify-content-center">

                                        <div class="product-cat">

                                            <span>Category:</span>

                                            <a href="products.php?category=<?= $product['category_id'] ?>">

                                                <?= htmlspecialchars($product['category_name']) ?>

                                            </a>

                                        </div>


                                        <div class="social-icons social-icons-sm">

                                            <span class="social-label">
                                                Share:
                                            </span>

                                            <a href="#" class="social-icon" title="Facebook" target="_blank">
                                                <i class="icon-facebook-f"></i>
                                            </a>

                                            <a href="#" class="social-icon" title="Twitter" target="_blank">
                                                <i class="icon-twitter"></i>
                                            </a>

                                            <a href="#" class="social-icon" title="Instagram" target="_blank">
                                                <i class="icon-instagram"></i>
                                            </a>

                                            <a href="#" class="social-icon" title="Pinterest" target="_blank">
                                                <i class="icon-pinterest"></i>
                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                    <!-- End .product-details -->
                </div><!-- End .product-details-top -->

                <div class="container">
                    <div class="product-details-tab">
                        <ul class="nav nav-pills justify-content-center" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="product-desc-link" data-toggle="tab"
                                    href="#product-desc-tab" role="tab" aria-controls="product-desc-tab"
                                    aria-selected="true">Description</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="product-info-link" data-toggle="tab" href="#product-info-tab"
                                    role="tab" aria-controls="product-info-tab" aria-selected="false">Additional
                                    information</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="product-shipping-link" data-toggle="tab"
                                    href="#product-shipping-tab" role="tab" aria-controls="product-shipping-tab"
                                    aria-selected="false">Shipping & Returns</a>
                            </li>
                            <!-- <li class="nav-item">
                                <a class="nav-link" id="product-review-link" data-toggle="tab"
                                    href="#product-review-tab" role="tab" aria-controls="product-review-tab"
                                    aria-selected="false">Reviews (2)</a>
                            </li> -->
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="product-desc-tab" role="tabpanel"
                                aria-labelledby="product-desc-link">

                                <div class="product-desc-content">

                                    <h3>Product Information</h3>

                                    <p>
                                        <?= nl2br(htmlspecialchars($product['description'])) ?>
                                    </p>

                                </div>

                            </div>
                            <!-- .End .tab-pane -->
                            <div class="tab-pane fade" id="product-info-tab" role="tabpanel"
                                aria-labelledby="product-info-link">

                                <div class="product-desc-content">

                                    <h3>Product Information</h3>

                                    <ul>
                                        <li>
                                            <strong>Product:</strong>
                                            <?= htmlspecialchars($product['name']) ?>
                                        </li>

                                        <li>
                                            <strong>Category:</strong>
                                            <?= htmlspecialchars($product['category_name']) ?>
                                        </li>

                                        <li>
                                            <strong>Price:</strong>
                                            $<?= number_format($product['price'], 2) ?>
                                        </li>

                                        <li>
                                            <strong>Stock:</strong>
                                            <?= (int) $product['stock'] ?>
                                        </li>
                                    </ul>

                                </div>

                            </div><!-- .End .tab-pane -->
                            <div class="tab-pane fade" id="product-shipping-tab" role="tabpanel"
                                aria-labelledby="product-shipping-link">

                                <div class="product-desc-content">

                                    <h3>Delivery & Returns</h3>

                                    <p>
                                        We deliver products to customers across Pakistan.
                                        Delivery time depends on your location.
                                    </p>

                                    <p>
                                        If you receive a damaged or incorrect product,
                                        please contact our support team for return assistance.
                                    </p>

                                </div>

                            </div><!-- .End .tab-pane -->
                            <!-- <div class="tab-pane fade" id="product-review-tab" role="tabpanel"
                                aria-labelledby="product-review-link">
                                <div class="reviews">
                                    <h3>Reviews (2)</h3>
                                    <div class="review">
                                        <div class="row no-gutters">
                                            <div class="col-auto">
                                                <h4><a href="#">Samanta J.</a></h4>
                                                <div class="ratings-container">
                                                    <div class="ratings">
                                                        <div class="ratings-val" style="width: 80%;"></div>
                                                        
                                                    </div>
                                                </div>
                                                <span class="review-date">6 days ago</span>
                                            </div>
                                            <div class="col">
                                                <h4>Good, perfect size</h4>

                                                <div class="review-content">
                                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus
                                                        cum dolores assumenda asperiores facilis porro reprehenderit
                                                        animi culpa atque blanditiis commodi perspiciatis doloremque,
                                                        possimus, explicabo, autem fugit beatae quae voluptas!</p>
                                                </div>

                                                <div class="review-action">
                                                    <a href="#"><i class="icon-thumbs-up"></i>Helpful (2)</a>
                                                    <a href="#"><i class="icon-thumbs-down"></i>Unhelpful (0)</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="review">
                                        <div class="row no-gutters">
                                            <div class="col-auto">
                                                <h4><a href="#">John Doe</a></h4>
                                                <div class="ratings-container">
                                                    <div class="ratings">
                                                        <div class="ratings-val" style="width: 100%;"></div>
                                                        
                                                    </div>
                                                </div>
                                                <span class="review-date">5 days ago</span>
                                            </div>
                                            <div class="col">
                                                <h4>Very good</h4>

                                                <div class="review-content">
                                                    <p>Sed, molestias, tempore? Ex dolor esse iure hic veniam laborum
                                                        blanditiis laudantium iste amet. Cum non voluptate eos enim, ab
                                                        cumque nam, modi, quas iure illum repellendus, blanditiis
                                                        perspiciatis beatae!</p>
                                                </div>

                                                <div class="review-action">
                                                    <a href="#"><i class="icon-thumbs-up"></i>Helpful (0)</a>
                                                    <a href="#"><i class="icon-thumbs-down"></i>Unhelpful (0)</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div> -->
                        </div><!-- End .tab-content -->
                    </div><!-- End .product-details-tab -->
                </div><!-- End .container -->

                <div class="container">

                    <h2 class="title text-center mb-4">
                        You May Also Like
                    </h2>

                    <div class="owl-carousel owl-simple carousel-equal-height carousel-with-shadow" data-toggle="owl"
                        data-owl-options='{
            "nav": false,
            "dots": true,
            "margin": 20,
            "loop": false,
            "responsive": {
                "0": {
                    "items": 1
                },
                "480": {
                    "items": 2
                },
                "768": {
                    "items": 3
                },
                "992": {
                    "items": 4
                },
                "1200": {
                    "items": 4,
                    "nav": true,
                    "dots": false
                }
            }
        }'>

                        <?php foreach ($youMayAlsoLike as $relatedProduct): ?>

                            <div class="product product-7 text-center">

                                <figure class="product-media">

                                    <a href="product-detail.php?id=<?= $relatedProduct['id'] ?>">

                                        <img src="uploads/products/<?= htmlspecialchars($relatedProduct['image']) ?>"
                                            alt="<?= htmlspecialchars($relatedProduct['name']) ?>" class="product-image">

                                    </a>

                                    <div class="product-action-vertical">

                                        <a href="#" class="btn-product-icon btn-wishlist btn-expandable">
                                            <span>add to wishlist</span>
                                        </a>

                                        <a href="#" class="btn-product-icon btn-compare" title="Compare">
                                            <span>Compare</span>
                                        </a>

                                    </div>

                                    <div class="product-action">

                                        <a href="#" class="btn-product btn-cart">
                                            <span>add to cart</span>
                                        </a>

                                    </div>

                                </figure>

                                <div class="product-body">

                                    <div class="product-cat">

                                        <a href="products.php?category=<?= $relatedProduct['category_id'] ?>">
                                            <?= htmlspecialchars($relatedProduct['category_name']) ?>
                                        </a>

                                    </div>

                                    <h3 class="product-title">

                                        <a href="product-detail.php?id=<?= $relatedProduct['id'] ?>">

                                            <?= htmlspecialchars($relatedProduct['name']) ?>

                                        </a>

                                    </h3>

                                    <div class="product-price">

                                        $<?= number_format($relatedProduct['price'], 2) ?>

                                    </div>

                                </div>

                            </div>

                        <?php endforeach; ?>

                    </div>

                </div>


            </div><!-- End .page-content -->
        </main><!-- End .main -->

        <?php include '../includes/footer.php'; ?>
    </div><!-- End .page-wrapper -->
    <button id="scroll-top" title="Back to Top"><i class="icon-arrow-up"></i></button>


    <!-- Plugins JS File -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/jquery.hoverIntent.min.js"></script>
    <script src="assets/js/jquery.waypoints.min.js"></script>
    <script src="assets/js/superfish.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/bootstrap-input-spinner.js"></script>
    <script src="assets/js/jquery.elevateZoom.min.js"></script>
    <script src="assets/js/bootstrap-input-spinner.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <!-- Main JS File -->
    <script src="assets/js/main.js"></script>
</body>


<!-- molla/product-gallery.html  22 Nov 2019 10:03:29 GMT -->

</html>