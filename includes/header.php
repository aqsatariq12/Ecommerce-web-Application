<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Category.php';
require_once __DIR__ . '/../core/Cart.php';

Session::start();
$isLoggedIn = Auth::check();
$headerCategories = Category::getActiveCategories();
$headerCartItems = [];
$headerCartCount = 0;
$headerCartTotal = 0;

if ($isLoggedIn && Auth::isCustomer()) {

    $user = Auth::user();

    $headerCartItems = Cart::getCartItems($user['id']);

    foreach ($headerCartItems as $headerCartItem) {

        $headerCartCount += (int) $headerCartItem['quantity'];

        $headerCartTotal +=
            (float) $headerCartItem['unit_price'] *
            (int) $headerCartItem['quantity'];
    }
}

?>

<style>
    /* =========================================================
   HEADER SEARCH - CUSTOM
   ========================================================= */

    /* Header middle layout */
    .header-middle>.container {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Left side */
    .header-middle .header-left {
        display: flex;
        align-items: center;
    }

    /* Right side - SEARCH + CART */
    .header-middle .header-right {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        margin-left: auto;
    }


    /* =========================================================
   CUSTOM SEARCH WRAPPER
   ========================================================= */

    .custom-header-search {
        position: relative;

        width: 42px;
        height: 48px;

        display: flex;
        align-items: center;
        justify-content: center;

        flex-shrink: 0;
    }


    /* =========================================================
   SEARCH ICON BUTTON
   ========================================================= */

    .custom-search-toggle {
        width: 42px;
        height: 42px;

        padding: 0;
        margin: 0;

        border: 0;
        outline: none;

        background: transparent;

        display: flex;
        align-items: center;
        justify-content: center;

        cursor: pointer;

        color: #222;

        position: relative;
        z-index: 1002;
    }

    .custom-search-toggle:hover {
        color: #3399ff;
    }

    .custom-search-toggle:focus {
        outline: none;
    }

    .custom-search-toggle i {
        font-size: 21px;
        line-height: 1;
    }


    /* =========================================================
   SEARCH FORM
   ========================================================= */

    .custom-search-form {
        position: absolute;

        top: 50%;
        right: 0;

        width: 0;
        height: 46px;

        transform: translateY(-50%);

        opacity: 0;
        visibility: hidden;

        overflow: hidden;

        display: flex;
        align-items: center;

        background: #fff;

        border: 1px solid #dcdcdc;
        border-radius: 24px;

        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.10);

        z-index: 1001;

        transition:
            width 0.25s ease,
            opacity 0.2s ease,
            visibility 0.2s ease;
    }


    /*
   IMPORTANT:
   Search expands LEFT from the icon.
   Cart does NOT move.
*/
    .custom-header-search.is-open .custom-search-form {
        width: 280px;

        opacity: 1;
        visibility: visible;
    }


    /* Hide the original icon while input is open */
    .custom-header-search.is-open .custom-search-toggle {
        opacity: 0;
        visibility: hidden;
    }


    /* =========================================================
   SEARCH INPUT
   ========================================================= */

    .custom-search-form input {
        width: 100%;
        height: 44px;

        margin: 0;
        padding: 0 48px 0 16px;

        border: 0 !important;
        outline: none !important;

        background: transparent !important;

        box-shadow: none !important;

        font-family: inherit;
        font-size: 14px;
        color: #333;
    }

    .custom-search-form input::placeholder {
        color: #999;
    }


    /* =========================================================
   SEARCH SUBMIT BUTTON
   ========================================================= */

    .custom-search-submit {
        position: absolute;

        top: 0;
        right: 0;

        width: 45px;
        height: 44px;

        padding: 0;
        margin: 0;

        border: 0;
        outline: none;

        background: transparent;

        display: flex;
        align-items: center;
        justify-content: center;

        cursor: pointer;

        color: #222;
    }

    .custom-search-submit:hover {
        color: #3399ff;
    }

    .custom-search-submit:focus {
        outline: none;
    }

    .custom-search-submit i {
        font-size: 18px;
    }


    /* =========================================================
   CART
   ========================================================= */

    .header-middle .cart-dropdown {
        margin: 0 !important;
        padding: 0 !important;

        flex-shrink: 0;
    }

    .header-middle .cart-dropdown>a {
        margin: 0 !important;
    }


    /* =========================================================
   REMOVE OLD MOLLA DESKTOP SEARCH AREA
   ========================================================= */

    .header-middle .header-center {
        display: none !important;
    }


    /* =========================================================
   MOBILE
   ========================================================= */

    @media (max-width: 991px) {

        .header-middle>.container {
            min-height: 70px;
        }

        .header-middle .header-right {
            gap: 5px;
        }

        .custom-header-search {
            width: 40px;
            height: 44px;
        }

        .custom-search-toggle {
            width: 40px;
            height: 40px;
        }

        .custom-search-toggle i {
            font-size: 20px;
        }

        .custom-search-form {
            height: 42px;
        }

        .custom-header-search.is-open .custom-search-form {
            width: 220px;
        }

        .custom-search-form input {
            height: 40px;

            padding-left: 14px;
            padding-right: 42px;

            font-size: 13px;
        }

        .custom-search-submit {
            width: 40px;
            height: 40px;
        }

        .custom-search-submit i {
            font-size: 17px;
        }
    }


    /* =========================================================
   SMALL MOBILE
   ========================================================= */

    @media (max-width: 575px) {

        .header-middle>.container {
            padding-left: 15px;
            padding-right: 15px;
        }

        .header-middle .logo img {
            width: 90px;
            height: auto;
        }

        .header-middle .header-right {
            gap: 3px;
        }

        .custom-header-search.is-open .custom-search-form {
            width: 185px;
        }

        .custom-search-form input {
            font-size: 12px;
        }
    }

    html {
        scroll-behavior: smooth;
    }
    #newArrival, #dealsOutlet, #trendingProducts {
    scroll-margin-top: 100px;
}
</style>


<header class="header header-intro-clearance header-4">

    <!-- =====================================================
         HEADER TOP
         ===================================================== -->

    <div class="header-top">

        <div class="container">

            <div class="header-left">

                <a href="tel:#">
                    <i class="icon-phone"></i>
                    Call: +0123 456 789
                </a>

            </div>


            <div class="header-right">

                <ul class="top-menu">

                    <li>

                        <a href="#">
                            Links
                        </a>

                        <ul>

                            <!-- Currency -->
                            <li>

                                <div class="header-dropdown">

                                    <a href="#">
                                        USD
                                    </a>

                                    <div class="header-menu">

                                        <ul>
                                            <li>
                                                <a href="#">EUR</a>
                                            </li>

                                            <li>
                                                <a href="#">USD</a>
                                            </li>
                                        </ul>

                                    </div>

                                </div>

                            </li>


                            <!-- Language -->
                            <li>

                                <div class="header-dropdown">

                                    <a href="#">
                                        English
                                    </a>

                                    <div class="header-menu">

                                        <ul>

                                            <li>
                                                <a href="#">English</a>
                                            </li>

                                            <li>
                                                <a href="#">French</a>
                                            </li>

                                            <li>
                                                <a href="#">Spanish</a>
                                            </li>

                                        </ul>

                                    </div>

                                </div>

                            </li>


                            <!-- Login -->
                            <li>

                                <?php if ($isLoggedIn): ?>

                                    <form action="/public/logout.php" method="POST" style="display: inline;">
                                        <button type="submit" class="border-0 bg-transparent ml-3">
                                            Logout
                                        </button>
                                    </form>

                                <?php else: ?>

                                    <a href="login.php">
                                        Sign in / Sign up
                                    </a>

                                <?php endif; ?>



                            </li>

                        </ul>

                    </li>

                </ul>

            </div>

        </div>

    </div>


    <!-- =====================================================
         HEADER MIDDLE
         ===================================================== -->

    <div class="header-middle">

        <div class="container">


            <!-- ================= LEFT ================= -->

            <div class="header-left">

                <!-- Mobile Menu -->
                <button class="mobile-menu-toggler" type="button">

                    <span class="sr-only">
                        Toggle mobile menu
                    </span>

                    <i class="icon-bars"></i>

                </button>


                <!-- Logo -->
                <a href="index.php" class="logo">

                    <img src="assets/images/demos/demo-4/logo.png" alt="Molla Logo" width="105" height="25">

                </a>

            </div>


            <!-- =================================================
                 RIGHT = SEARCH + CART
                 ================================================= -->

            <div class="header-right">


                <!-- ================= SEARCH ================= -->

                <div class="custom-header-search" id="customHeaderSearch">

                    <!-- Search Icon -->

                    <button type="button" class="custom-search-toggle" id="customSearchToggle" aria-label="Search"
                        aria-expanded="false">

                        <i class="icon-search"></i>

                    </button>


                    <!-- Search Input -->

                    <form action="products.php" method="get" class="custom-search-form" id="customSearchForm">

                        <input type="search" name="q" id="customSearchInput" placeholder="Search product..."
                            autocomplete="off" required>


                        <button type="submit" class="custom-search-submit" aria-label="Submit search">

                            <i class="icon-search"></i>

                        </button>

                    </form>

                </div>

                <!-- ================= END SEARCH ================= -->


                <!-- ================= CART ================= -->

                <div class="dropdown cart-dropdown">

                    <a href="cart.php" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" data-display="static">

                        <div class="icon">

                            <i class="icon-shopping-cart"></i>

                            <span class="cart-count">
                                <?= $headerCartCount ?>
                            </span>

                        </div>

                        <p>
                            Cart
                        </p>

                    </a>


                    <!-- Cart Dropdown -->

                    <div class="dropdown-menu dropdown-menu-right">

                        <div class="dropdown-cart-products">

                            <?php if (!empty($headerCartItems)): ?>

                                <?php foreach ($headerCartItems as $headerCartItem): ?>

                                    <div class="product">

                                        <div class="product-cart-details">

                                            <h4 class="product-title">

                                                <a href="product-detail.php?id=<?= (int) $headerCartItem['product_id'] ?>">
                                                    <?= htmlspecialchars($headerCartItem['name']) ?>
                                                </a>

                                            </h4>

                                            <span class="cart-product-info">

                                                <span class="cart-product-qty">
                                                    <?= (int) $headerCartItem['quantity'] ?>
                                                </span>

                                                x $<?= number_format((float) $headerCartItem['unit_price'], 2) ?>

                                            </span>

                                        </div>


                                        <figure class="product-image-container">

                                            <a href="product-detail.php?id=<?= (int) $headerCartItem['product_id'] ?>"
                                                class="product-image">

                                                <img src="uploads/products/<?= htmlspecialchars($headerCartItem['image']) ?>"
                                                    alt="<?= htmlspecialchars($headerCartItem['name']) ?>">

                                            </a>

                                        </figure>


                                        <a href="cart.php" class="btn-remove" title="Remove Product">

                                            <i class="icon-close"></i>

                                        </a>

                                    </div>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <p class="text-center p-3 mb-0">
                                    Your cart is empty.
                                </p>

                            <?php endif; ?>

                        </div>


                        <!-- Cart Total -->

                        <div class="dropdown-cart-total">

                            <span>
                                Total
                            </span>

                            <span class="cart-total-price">
                                $<?= number_format($headerCartTotal, 2) ?>
                            </span>

                        </div>


                        <!-- Cart Buttons -->

                        <div class="dropdown-cart-action">

                            <a href="cart.php" class="btn btn-primary">
                                View Cart
                            </a>


                            <a href="checkout.php" class="btn btn-outline-primary-2">

                                <span>
                                    Checkout
                                </span>

                                <i class="icon-long-arrow-right"></i>

                            </a>

                        </div>

                    </div>

                </div>

                <!-- ================= END CART ================= -->


            </div>

            <!-- ================= END HEADER RIGHT ================= -->

        </div>

    </div>

    <!-- =====================================================
         END HEADER MIDDLE
         ===================================================== -->



    <!-- =====================================================
         HEADER BOTTOM
         ===================================================== -->

    <div class="header-bottom sticky-header">

        <div class="container">


            <!-- ================= CATEGORIES ================= -->

            <div class="header-left">

                <div class="dropdown category-dropdown">

                    <a href="#" class="dropdown-toggle" role="button" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false" data-display="static" title="Browse Categories">

                        Browse Categories



                    </a>


                    <div class="dropdown-menu">

                        <nav class="side-nav">

                            <ul class="menu-vertical sf-arrows">

                                <!-- All Products -->

                                <li class="item-lead">

                                    <a href="all_products.php">
                                        All Products
                                    </a>

                                </li>


                                <!-- Categories -->

                                <?php foreach ($headerCategories as $headerCategory): ?>

                                    <li>

                                        <a href="products.php?category=<?= (int) $headerCategory['id'] ?>">

                                            <?= htmlspecialchars($headerCategory['name']) ?>

                                        </a>

                                    </li>

                                <?php endforeach; ?>

                            </ul>

                        </nav>

                    </div>

                </div>

            </div>


            <!-- ================= MAIN NAV ================= -->

            <div class="">

                <nav class="main-nav">

                    <ul class="menu sf-arrows">


                        <!-- HOME -->

                        <li class="active">

                            <a href="index.php">
                                Home
                            </a>

                        </li>


                        <!-- SHOP -->

                        <li>

                            <a href="#" class="sf-with-ul">
                                Shop
                            </a>


                            <div class="megamenu megamenu-md">

                                <div class="row no-gutters">


                                    <!-- Shop Menu -->

                                    <div class="col-md-8 shop-menu-content">

                                        <div class="menu-col">

                                            <div class="row">


                                                <!-- Column 1 -->

                                                <div class="col-md-6">

                                                    <div class="menu-title">
                                                        Shop
                                                    </div>

                                                    <ul>

                                                        <li>
                                                            <a href="all_products.php">
                                                                All Products
                                                            </a>
                                                        </li>



                                                    </ul>


                                                    <div class="menu-title">
                                                        Shopping
                                                    </div>

                                                    <ul>

                                                        <li>
                                                            <a href="cart.php">
                                                                Cart
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="checkout.php">
                                                                Checkout
                                                            </a>
                                                        </li>

                                                    </ul>

                                                </div>


                                                <!-- Column 2 -->

                                                <div class="col-md-6">

                                                    <div class="menu-title">
                                                        Order
                                                    </div>

                                                    <ul>

                                                        <li>
                                                            <a href="order-confirmation.php">
                                                                Order Confirmation
                                                            </a>
                                                        </li>

                                                    </ul>


                                                    <div class="menu-title">
                                                        Account
                                                    </div>

                                                    <ul>

                                                        <li>

                                                            <?php if ($isLoggedIn): ?>

                                                                <form action="/public/logout.php" method="POST">
                                                                    <button type="submit"
                                                                        class="border-0 bg-transparent p-0">
                                                                        Logout
                                                                    </button>
                                                                </form>

                                                            <?php else: ?>

                                                                <a href="login.php">
                                                                    Sign In / Sign Up
                                                                </a>

                                                            <?php endif; ?>

                                                        </li>

                                                    </ul>

                                                </div>

                                            </div>

                                        </div>

                                    </div>


                                    <!-- Banner -->

                                    <div class="col-md-4 shop-menu-banner">

                                        <div class="banner banner-overlay">

                                            <a href="products.php" class="banner banner-menu">

                                                <img src="assets/images/menu/banner-1.png" alt="Banner">


                                                <div class="banner-content banner-content-top">



                                                </div>

                                            </a>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </li>


                        <!-- PRODUCT -->

                        <li>

                            <a href="orders.php">
                                My Order
                            </a>

                        </li>
                        <li>
                            <a href="index.php#newArrival">
                                New Arrivals
                            </a>
                        </li>
                        <li>

                            <a href="index.php#dealsOutlet">
                                Deals/Outlet
                            </a>

                        </li>
                        <li>

                            <a href="index.php#trendingProducts">
                                Trending Products
                            </a>

                        </li>


                    </ul>

                </nav>

            </div>


        </div>

    </div>

    <!-- =====================================================
         END HEADER BOTTOM
         ===================================================== -->

</header>



<!-- =========================================================
     MOBILE MENU OVERLAY
     ========================================================= -->

<div class="mobile-menu-overlay"></div>


<!-- =========================================================
     MOBILE MENU
     ========================================================= -->

<div class="mobile-menu-container mobile-menu-light">

    <div class="mobile-menu-wrapper">


        <!-- Close -->

        <span class="mobile-menu-close">

            <i class="icon-close"></i>

        </span>


        <!-- Mobile Search -->

        <form action="products.php" method="get" class="mobile-search">

            <label for="mobile-search" class="sr-only">
                Search
            </label>


            <input type="search" class="form-control" name="q" id="mobile-search" placeholder="Search in..." required>


            <button class="btn btn-primary" type="submit">

                <i class="icon-search"></i>

            </button>

        </form>


        <!-- Mobile Tabs -->

        <ul class="nav nav-pills-mobile nav-border-anim" role="tablist">

            <li class="nav-item">

                <a class="nav-link active" id="mobile-menu-link" data-toggle="tab" href="#mobile-menu-tab" role="tab"
                    aria-controls="mobile-menu-tab" aria-selected="true">
                    Menu
                </a>

            </li>


            <li class="nav-item">

                <a class="nav-link" id="mobile-cats-link" data-toggle="tab" href="#mobile-cats-tab" role="tab"
                    aria-controls="mobile-cats-tab" aria-selected="false">
                    Categories
                </a>

            </li>

        </ul>


        <!-- Mobile Tab Content -->

        <div class="tab-content">


            <!-- ================= MOBILE MENU TAB ================= -->

            <div class="tab-pane fade show active" id="mobile-menu-tab" role="tabpanel"
                aria-labelledby="mobile-menu-link">

                <nav class="mobile-nav">

                    <ul class="mobile-menu">


                        <li class="active">

                            <a href="index.php">
                                Home
                            </a>

                        </li>


                        <li>

                            <a href="products.php">
                                Shop
                            </a>


                            <ul>

                                <li>
                                    <a href="all_products.php">
                                        All Products
                                    </a>
                                </li>

                                <li>
                                    <a href="cart.php">
                                        Cart
                                    </a>
                                </li>

                                <li>
                                    <a href="checkout.php">
                                        Checkout
                                    </a>
                                </li>

                                <li>
                                    <a href="order-confirmation.php">
                                        Order Confirmation
                                    </a>
                                </li>

                            </ul>

                        </li>


                        <li>

                            <a href="product-detail.php">
                                Product
                            </a>

                        </li>

                        <li>

                            <?php if ($isLoggedIn): ?>

                                <form action="/public/logout.php" method="POST">
                                    <button type="submit" class="border-0 bg-transparent ml-4">
                                        Logout
                                    </button>
                                </form>

                            <?php else: ?>

                                <a href="login.php">
                                    SignIn / SignUp
                                </a>

                            <?php endif; ?>

                        </li>

                    </ul>

                </nav>

            </div>


            <!-- ================= MOBILE CATEGORIES TAB ================= -->

            <div class="tab-pane fade" id="mobile-cats-tab" role="tabpanel" aria-labelledby="mobile-cats-link">

                <nav class="mobile-cats-nav">

                    <ul class="mobile-cats-menu">

                        <!-- All Products -->

                        <li>

                            <a class="mobile-cats-lead" href="all_products.php">
                                All Products
                            </a>

                        </li>


                        <!-- Categories -->

                        <?php foreach ($headerCategories as $headerCategory): ?>

                            <li>

                                <a href="products.php?category=<?= (int) $headerCategory['id'] ?>">

                                    <?= htmlspecialchars($headerCategory['name']) ?>

                                </a>

                            </li>

                        <?php endforeach; ?>

                    </ul>

                </nav>

            </div>


        </div>


        <!-- ================= SOCIAL ICONS ================= -->

        <div class="social-icons">


            <a href="#" class="social-icon" target="_blank" title="Facebook">
                <i class="icon-facebook-f"></i>
            </a>


            <a href="#" class="social-icon" target="_blank" title="Twitter">
                <i class="icon-twitter"></i>
            </a>


            <a href="#" class="social-icon" target="_blank" title="Instagram">
                <i class="icon-instagram"></i>
            </a>


            <a href="#" class="social-icon" target="_blank" title="Youtube">
                <i class="icon-youtube"></i>
            </a>


        </div>


    </div>

</div>




<!-- =========================================================
     CUSTOM SEARCH JAVASCRIPT
     ========================================================= -->

<script>
    document.addEventListener("DOMContentLoaded", function () {

        const searchContainer = document.getElementById("customHeaderSearch");
        const searchToggle = document.getElementById("customSearchToggle");
        const searchInput = document.getElementById("customSearchInput");

        if (!searchContainer || !searchToggle || !searchInput) {
            return;
        }


        /* =====================================================
           OPEN SEARCH
           ===================================================== */

        searchToggle.addEventListener("click", function (event) {

            event.preventDefault();
            event.stopPropagation();

            const isOpen = searchContainer.classList.toggle("is-open");

            searchToggle.setAttribute(
                "aria-expanded",
                isOpen ? "true" : "false"
            );


            if (isOpen) {

                setTimeout(function () {

                    searchInput.focus();

                }, 200);

            }

        });


        /* =====================================================
           PREVENT FORM CLICK FROM CLOSING SEARCH
           ===================================================== */

        searchInput.addEventListener("click", function (event) {

            event.stopPropagation();

        });


        /* =====================================================
           CLICK OUTSIDE = CLOSE
           ===================================================== */

        document.addEventListener("click", function (event) {

            if (!searchContainer.contains(event.target)) {

                searchContainer.classList.remove("is-open");

                searchToggle.setAttribute(
                    "aria-expanded",
                    "false"
                );

            }

        });


        /* =====================================================
           ESCAPE = CLOSE
           ===================================================== */

        document.addEventListener("keydown", function (event) {

            if (event.key === "Escape") {

                searchContainer.classList.remove("is-open");

                searchToggle.setAttribute(
                    "aria-expanded",
                    "false"
                );

                searchToggle.focus();

            }

        });

    });
</script>